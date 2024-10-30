<?php

declare(strict_types=1);

/*
 * Copyright (C) 2020-2024 Iain Cambridge
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU LESSER GENERAL PUBLIC LICENSE as published by
 * the Free Software Foundation, either version 2.1 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Obol\Provider\Stripe;

use Brick\Money\Currency;
use Brick\Money\Money;
use Obol\Exception\PaymentFailureException;
use Obol\Exception\ProviderFailureException;
use Obol\Model\BillingDetails;
use Obol\Model\CancelSubscription;
use Obol\Model\CardFile;
use Obol\Model\CardOnFileResponse;
use Obol\Model\Charge;
use Obol\Model\ChargeCardResponse;
use Obol\Model\Customer;
use Obol\Model\CustomerCreation;
use Obol\Model\Enum\ChargeFailureReasons;
use Obol\Model\FrontendCardProcess;
use Obol\Model\PaymentDetails;
use Obol\Model\Subscription;
use Obol\Model\SubscriptionCancellation;
use Obol\Model\SubscriptionCreationResponse;
use Obol\PaymentServiceInterface;
use Obol\Provider\ProviderInterface;
use Psr\Log\LoggerAwareTrait;
use Stripe\Exception\CardException;
use Stripe\StripeClient;

class PaymentService implements PaymentServiceInterface
{
    use LoggerAwareTrait;

    protected StripeClient $stripe;

    protected Config $config;

    protected ProviderInterface $provider;

    public function __construct(ProviderInterface $provider, Config $config, ?StripeClient $stripe = null)
    {
        $this->provider = $provider;
        $this->config = $config;
        $this->stripe = $stripe ?? new StripeClient($this->config->getApiKey());
    }

    public function startSubscription(Subscription $subscription): SubscriptionCreationResponse
    {
        $this->logger?->info('Starting subscription', ['customer' => $subscription->getCustomerReference()]);
        if (!$subscription->hasPriceId()) {
            throw new \Exception('EmbeddedSubscription must has price id for stripe');
        }
        $customerCreation = null;
        if (!$subscription->getBillingDetails()->hasCustomerReference()) {
            $customerCreation = $this->setCustomerReference($subscription->getBillingDetails());
        }

        if (!$subscription->getBillingDetails()->hasStoredPaymentReference()) {
            $cardOnFileResponse = $this->createCardOnFile($subscription->getBillingDetails());
            $customerCreation = $cardOnFileResponse->getCustomerCreation();
        }

        try {
            $payload = [
                'payment_behavior' => 'error_if_incomplete',
                'customer' => $subscription->getBillingDetails()->getCustomerReference(),
                'items' => [['price' => $subscription->getPriceId(), 'quantity' => $subscription->getSeats()]],
            ];

            if ($subscription->hasTrial()) {
                $payload['trial_period_days'] = $subscription->getTrialLengthDays();
            }

            if ($subscription->hasStoredPaymentReference()) {
                $payload['default_payment_method'] = $subscription->getStoredPaymentReference();
            }

            if (!$subscription->getParentReference()) {
                $this->logger?->info('Creating parent subscription', ['customer' => $subscription->getCustomerReference()]);
                $stripeSubscription = $this->stripe->subscriptions->create(
                    $payload
                );
                $subscriptionId = $stripeSubscription->id;
                $lineId = $stripeSubscription->items->first()->id;
                $billedUntil = new \DateTime();
                $billedUntil->setTimestamp($stripeSubscription->current_period_end);
            } else {
                $this->logger?->info('Creating child subscription', ['customer' => $subscription->getCustomerReference()]);
                $stripeSubscription = $this->stripe->subscriptionItems->create([
                    'subscription' => $subscription->getParentReference(),
                    'price' => $subscription->getPriceId(),
                    'quantity' => $subscription->getSeats(),
                ]);
                $subscriptionId = $subscription->getParentReference();
                $lineId = $stripeSubscription->id;
                $payload = ['billing_cycle_anchor' => 'now', 'proration_behavior' => 'create_prorations'];
                if ($subscription->hasStoredPaymentReference()) {
                    $payload['default_source'] = $subscription->getStoredPaymentReference();
                }

                $stripeSubscription = $this->stripe->subscriptions->update($subscriptionId, $payload);
                $billedUntil = new \DateTime();
                $billedUntil->setTimestamp($stripeSubscription->current_period_end);
            }
        } catch (CardException $exception) {
            $this->logger?->warning('Got a card decline response from stripe for subscription start', ['decline_code' => $exception->getDeclineCode()]);
            $reason = match ($exception->getDeclineCode()) {
                'authentication_required' => ChargeFailureReasons::AUTHENTICATION_REQUIRED,
                'invalid_account', 'currency_not_supported', 'incorrect_number', 'incorrect_cvc', 'incorrect_pin', 'incorrect_zip', 'card_not_supported', 'invalid_amount', 'invalid_cvc', 'invalid_number', 'invalid_expiry_month', 'invalid_expiry_year' => ChargeFailureReasons::INVALID_DETAILS,
                'call_issuer', 'do_not_honor', 'do_not_try_again', 'new_account_information_available', 'no_action_taken', 'not_permitted' => ChargeFailureReasons::CONTACT_PROVIDER,
                'insufficient_funds' => ChargeFailureReasons::LACK_OF_FUNDS,
                'expired_card' => ChargeFailureReasons::EXPIRED_CARD,
                default => ChargeFailureReasons::GENERAL_DECLINE,
            };
            throw new PaymentFailureException($reason, $exception);
        } catch (\Throwable $exception) {
            $this->logger?->warning('Got a general failure response from stripe for subscription start', ['exception_message' => $exception->getMessage()]);
            throw new ProviderFailureException(message: $exception->getMessage(), previous: $exception);
        }

        if (true === $stripeSubscription->livemode) {
            $url = sprintf('https://dashboard.stripe.com/subscriptions/%s', $stripeSubscription->id);
        } else {
            $url = sprintf('https://dashboard.stripe.com/test/subscriptions/%s', $stripeSubscription->id);
        }

        $subscriptionCreation = new SubscriptionCreationResponse();
        $subscriptionCreation->setCustomerCreation($customerCreation);
        $subscriptionCreation->setSubscriptionId($subscriptionId)
            ->setLineId($lineId)
            ->setBilledUntil($billedUntil)
            ->setDetailsUrl($url);

        if (!$subscription->hasTrial()) {
            $charges = $this->stripe->charges->all([
                'customer' => $subscription->getBillingDetails()->getCustomerReference(),
                'limit' => 1,
            ]);
            /** @var \Stripe\Charge $charge */
            $charge = $charges->first();
            $paymentDetails = new PaymentDetails();
            $paymentDetails->setAmount(Money::ofMinor($charge->amount, Currency::of(strtoupper($charge->currency))));
            $paymentDetails->setStoredPaymentReference($subscription->getBillingDetails()->getStoredPaymentReference());
            $paymentDetails->setPaymentReference($charge->id);
            $paymentDetails->setCustomerReference($subscription->getBillingDetails()->getCustomerReference());

            if (true === $stripeSubscription->livemode) {
                $url = sprintf('https://dashboard.stripe.com/payments/%s', $charge->id);
            } else {
                $url = sprintf('https://dashboard.stripe.com/test/payments/%s', $charge->id);
            }
            $paymentDetails->setPaymentReferenceLink($url);
            $subscriptionCreation->setPaymentDetails($paymentDetails);
        }

        return $subscriptionCreation;
    }

    public function stopSubscription(CancelSubscription $cancelSubscription): SubscriptionCancellation
    {
        try {
            $this->logger?->info('Starting subscription stop process', ['subscription' => $cancelSubscription->getSubscription()->getId()]);
            $payload = [];
            $cancellation = new SubscriptionCancellation();
            $cancellation->setSubscription($cancelSubscription->getSubscription());

            $stripeSubscription = $this->stripe->subscriptions->retrieve($cancelSubscription->getSubscription()->getId());

            if ($cancelSubscription->getSubscription()->hasLineId() && 1 !== $stripeSubscription->items->count()) {
                if ($cancelSubscription->isInstantCancel()) {
                    $payload['proration_behavior'] = 'none';
                }
                $this->stripe->subscriptionItems->delete($cancelSubscription->getSubscription()->getLineId(), $payload);
                $payload = ['billing_cycle_anchor' => 'now', 'proration_behavior' => 'create_prorations'];
                $stripeSubscription = $this->stripe->subscriptions->update($cancelSubscription->getSubscription()->getId(), $payload);
            } else {
                if ($cancelSubscription->isInstantCancel()) {
                    $payload['invoice_now'] = true;
                }

                if (is_string($cancelSubscription->getComment())) {
                    $payload['cancellation_details'] = ['comment' => $cancelSubscription->getComment()];
                }

                $stripeSubscription = $this->stripe->subscriptions->cancel($cancelSubscription->getSubscription()->getId(), $payload);
                $cancellation = new SubscriptionCancellation();
                $cancellation->setSubscription($cancelSubscription->getSubscription());
            }

            return $cancellation;
        } catch (\Throwable $exception) {
            $this->logger?->warning('Received a general failure from stripe for subscription stop', ['exception_message' => $exception->getMessage()]);
            throw new ProviderFailureException(previous: $exception);
        }
    }

    public function createCardOnFile(BillingDetails $billingDetails): CardOnFileResponse
    {
        $this->logger?->info('Creating a card on stripe', ['customer' => $billingDetails->getCustomerReference()]);
        $customerCreation = null;
        if (!$billingDetails->hasCustomerReference()) {
            $customerCreation = $this->setCustomerReference($billingDetails);
        }
        if ($this->config->isPciMode()) {
            $this->logger?->info('Creating a card on stripe via PCI mode', ['customer' => $billingDetails->getCustomerReference()]);
            $payload = [
                'source' => [
                    'object' => 'card',
                    'number' => $billingDetails->getCardDetails()->getNumber(),
                    'exp_month' => $billingDetails->getCardDetails()->getExpireDate(),
                    'exp_year' => $billingDetails->getCardDetails()->getExpireYear(),
                    'cvc' => $billingDetails->getCardDetails()->getSecurityCode(),
                    'name' => $billingDetails->getCardDetails()->getName(),
                    'address_line1' => $billingDetails->getAddress()->getStreetLineOne(),
                    'address_line2' => $billingDetails->getAddress()->getStreetLineTwo(),
                    'address_city' => $billingDetails->getAddress()->getCity(),
                    'address_state' => $billingDetails->getAddress()->getState(),
                    'address_zip' => $billingDetails->getAddress()->getPostalCode(),
                    'address_country' => $billingDetails->getAddress()->getCountryCode(),
                ],
            ];
            try {
                $cardData = $this->stripe->customers->createSource($billingDetails->getCustomerReference(), $payload);
            } catch (\Throwable $exception) {
                $this->logger?->warning('Received a general failure from stripe for creating a card', ['exception_message' => $exception->getMessage()]);
                throw new ProviderFailureException(previous: $exception);
            }
        } else {
            $this->logger?->info('Creating a card on stripe via token mode', ['customer' => $billingDetails->getCustomerReference()]);
            if (!$billingDetails->getCardDetails()->hasToken()) {
                throw new \Exception('No token');
            }
            $payload = ['source' => $billingDetails->getCardDetails()->getToken()];

            try {
                $cardData = $this->stripe->customers->createSource($billingDetails->getCustomerReference(), $payload);
            } catch (\Throwable $exception) {
                $this->logger?->warning('Received general error from stripe', ['exception_message' => $exception->getMessage()]);
                throw new ProviderFailureException(previous: $exception);
            }
        }

        $this->logger?->info('Card successfully created on stripe', ['customer' => $billingDetails->getCustomerReference(), 'card' => $cardData->id]);

        $cardFile = new CardFile();
        $cardFile->setCustomerReference($billingDetails->getCustomerReference())
            ->setStoredPaymentReference($cardData->id)
            ->setBrand($cardData->brand)
            ->setLastFour((string) $cardData->last4)
            ->setExpiryMonth((string) $cardData->exp_month)
            ->setExpiryYear((string) $cardData->exp_year);

        $cardOnFile = new CardOnFileResponse();
        $cardOnFile->setCardFile($cardFile);
        $cardOnFile->setCustomerCreation($customerCreation);

        return $cardOnFile;
    }

    public function deleteCardFile(BillingDetails $cardFile): void
    {
        try {
            $this->logger?->info('Deleting a card on stripe', ['customer' => $cardFile->getCustomerReference(), 'card' => $cardFile->getStoredPaymentReference()]);
            $this->stripe->paymentMethods->detach($cardFile->getStoredPaymentReference());
        } catch (\Throwable $exception) {
            $this->logger?->info('Received a general error from stripe', ['exception_message' => $exception->getMessage()]);
            throw new ProviderFailureException(previous: $exception);
        }
    }

    public function chargeCardOnFile(Charge $cardFile): ChargeCardResponse
    {
        // TODO add sanity check
        try {
            $payload = [
                'customer' => $cardFile->getBillingDetails()->getCustomerReference(),
                'amount' => $cardFile->getAmount()->getMinorAmount()->toInt(),
                'currency' => $cardFile->getAmount()->getCurrency()->getCurrencyCode(),
                'source' => $cardFile->getBillingDetails()->getStoredPaymentReference(),
                'description' => $cardFile->getName(),
            ];

            $this->logger?->info('Making a card to card request', $payload);
            $chargeData = $this->stripe->charges->create($payload);
        } catch (CardException $exception) {
            $json = $exception->getJsonBody();
            $declineCode = $json['code'] ?? null;

            $this->logger?->warning('Got a card decline response from stripe for charge card', ['decline_code' => $declineCode]);
            $charge = new ChargeCardResponse();
            $charge->setSuccessful(false);
            $reason = match ($declineCode) {
                'authentication_required' => ChargeFailureReasons::AUTHENTICATION_REQUIRED,
                'invalid_account', 'currency_not_supported', 'incorrect_number', 'incorrect_cvc', 'incorrect_pin', 'incorrect_zip', 'card_not_supported', 'invalid_amount', 'invalid_cvc', 'invalid_number', 'invalid_expiry_month', 'invalid_expiry_year' => ChargeFailureReasons::INVALID_DETAILS,
                'call_issuer', 'do_not_honor', 'do_not_try_again', 'new_account_information_available', 'no_action_taken', 'not_permitted' => ChargeFailureReasons::CONTACT_PROVIDER,
                'insufficient_funds' => ChargeFailureReasons::LACK_OF_FUNDS,
                'expired_card' => ChargeFailureReasons::EXPIRED_CARD,
                default => ChargeFailureReasons::GENERAL_DECLINE,
            };
            throw new PaymentFailureException($reason, $exception);
        } catch (\Throwable $exception) {
            $this->logger?->warning('Received a general error from stripe', ['exception_message' => $exception->getMessage()]);
            throw new ProviderFailureException(previous: $exception);
        }

        $this->logger?->info('Successfully charged card', ['customer' => $cardFile->getBillingDetails()->getCustomerReference(), 'charge' => $chargeData->id]);

        $paymentDetails = new PaymentDetails();
        $paymentDetails->setAmount($cardFile->getAmount());
        $paymentDetails->setStoredPaymentReference($cardFile->getBillingDetails()->getStoredPaymentReference());
        $paymentDetails->setPaymentReference($chargeData->id);
        $paymentDetails->setCustomerReference($cardFile->getBillingDetails()->getCustomerReference());

        $chargeCardResponse = new ChargeCardResponse();
        $chargeCardResponse->setPaymentDetails($paymentDetails);
        $chargeCardResponse->setSuccessful(true);

        return $chargeCardResponse;
    }

    public function startFrontendCreateCardOnFile(BillingDetails $billingDetails): FrontendCardProcess
    {
        $this->logger?->info('Starting front end create card on file');
        $customerCreation = null;
        if (!$billingDetails->hasCustomerReference()) {
            $customerCreation = $this->setCustomerReference($billingDetails);
        }
        try {
            $intentData = $this->stripe->setupIntents->create(['payment_method_types' => $this->config->getPaymentMethods(), 'customer' => $billingDetails->getCustomerReference()]);
        } catch (\Throwable $exception) {
            $this->logger?->warning('Received a general error from stripe', ['exception_message' => $exception->getMessage()]);
            throw new ProviderFailureException(previous: $exception);
        }

        $process = new FrontendCardProcess();
        $process->setToken($intentData->client_secret);
        $process->setCustomerReference($billingDetails->getCustomerReference());
        $process->setCustomerCreation($customerCreation);

        return $process;
    }

    public function makeCardDefault(BillingDetails $billingDetails): void
    {
        $this->logger?->info('Make card default stripe');
        if (str_starts_with($billingDetails->getStoredPaymentReference(), 'pm')) {
            $payload = ['invoice_settings' => ['default_payment_method' => $billingDetails->getStoredPaymentReference()]];
        } else {
            $payload = ['default_source' => $billingDetails->getStoredPaymentReference()];
        }
        try {
            $this->stripe->customers->update($billingDetails->getCustomerReference(), $payload);
        } catch (\Throwable $e) {
            $this->logger?->warning('Received a general error from stripe', ['exception_message' => $e->getMessage()]);
            throw new ProviderFailureException($e->getMessage(), previous: $e);
        }
    }

    public function list(int $limit = 10, ?string $lastId = null): array
    {
        $payload = ['limit' => $limit];
        if (isset($lastId) && !empty($lastId)) {
            $payload['starting_after'] = $lastId;
        }

        $result = $this->stripe->charges->all($payload);
        $output = [];
        foreach ($result->data as $charge) {
            $money = Money::ofMinor($charge->amount, strtoupper($charge->currency));
            $paymentDetails = new PaymentDetails();
            $paymentDetails->setPaymentReference($charge->id);
            $paymentDetails->setStoredPaymentReference($charge->payment_method);
            $paymentDetails->setCustomerReference($charge->customer);
            $paymentDetails->setInvoiceReference($charge->invoice);
            $paymentDetails->setAmount($money);

            if (true === $charge->livemode) {
                $url = sprintf('https://dashboard.stripe.com/payments/%s', $charge->id);
            } else {
                $url = sprintf('https://dashboard.stripe.com/test/payments/%s', $charge->id);
            }
            $paymentDetails->setPaymentReferenceLink($url);

            $createdAt = new \DateTime();
            $createdAt->setTimestamp($charge->created);
            $paymentDetails->setCreatedAt($createdAt);
            $output[] = $paymentDetails;
        }

        return $output;
    }

    private function setCustomerReference(BillingDetails $billingDetails): CustomerCreation
    {
        $customer = new Customer();
        $customer->setEmail($billingDetails->getEmail());
        $customer->setName($billingDetails->getName());
        $customer->setAddress($billingDetails->getAddress());

        try {
            $this->logger?->info('Creating a customer on stripe');
            $customerCreation = $this->provider->customers()->create($customer);
        } catch (\Throwable $exception) {
            $this->logger?->warning('General error received from stripe', ['exception_message' => $exception->getMessage()]);
            throw new ProviderFailureException(previous: $exception);
        }

        $billingDetails->setCustomerReference($customerCreation->getReference());

        return $customerCreation;
    }
}
