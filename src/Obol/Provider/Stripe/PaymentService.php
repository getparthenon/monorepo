<?php

declare(strict_types=1);

/*
 * Copyright Iain Cambridge 2020-2023.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: TBD ( 3 years after 2.2.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace Obol\Provider\Stripe;

use Obol\Model\BillingDetails;
use Obol\Model\CardFile;
use Obol\Model\CardOnFileResponse;
use Obol\Model\Charge;
use Obol\Model\ChargeCardResponse;
use Obol\Model\Customer;
use Obol\Model\CustomerCreation;
use Obol\Model\FrontendCardProcess;
use Obol\Model\PaymentDetails;
use Obol\Model\Subscription;
use Obol\Model\SubscriptionCreationResponse;
use Obol\PaymentServiceInterface;
use Obol\Provider\ProviderInterface;
use Stripe\StripeClient;

class PaymentService implements PaymentServiceInterface
{
    protected StripeClient $stripe;

    protected Config $config;

    protected ProviderInterface $provider;

    /**
     * @param StripeClient $stripe
     */
    public function __construct(ProviderInterface $provider, Config $config, ?StripeClient $stripe = null)
    {
        $this->provider = $provider;
        $this->config = $config;
        $this->stripe = $stripe ?? new StripeClient($this->config->getApiKey());
    }

    public function startSubscription(Subscription $subscription): SubscriptionCreationResponse
    {
        if (!$subscription->hasPriceId()) {
            throw new \Exception('Subscription must has price id for stripe');
        }
        $customerCreation = null;
        if (!$subscription->getBillingDetails()->hasCustomerReference()) {
            $customerCreation = $this->setCustomerReference($subscription->getBillingDetails());
        }

        if (!$subscription->getBillingDetails()->hasStoredPaymentReference()) {
            $cardOnFileResponse = $this->createCardOnFile($subscription->getBillingDetails());
            $customerCreation = $cardOnFileResponse->getCustomerCreation();
        }

        $stripeSubscription = $this->stripe->subscriptions->create(
            [
                'customer' => $subscription->getBillingDetails()->getCustomerReference(),
                'items' => [['price' => $subscription->getPriceId(), 'quantity' => $subscription->getSeats()]],
            ]
        );

        $charges = $this->stripe->charges->all([
            'customer' => $subscription->getBillingDetails()->getCustomerReference(),
            'limit' => 1,
        ]);
        /** @var \Stripe\Charge $charge */
        $charge = $charges->first();

        $paymentDetails = new PaymentDetails();
        $paymentDetails->setAmount($subscription->getTotalCost());
        $paymentDetails->setStoredPaymentReference($subscription->getBillingDetails()->getStoredPaymentReference());
        $paymentDetails->setPaymentReference($charge->id);
        $paymentDetails->setCustomerReference($subscription->getBillingDetails()->getCustomerReference());

        $subscriptionCreation = new SubscriptionCreationResponse();
        $subscriptionCreation->setCustomerCreation($customerCreation);
        $subscriptionCreation->setSubscriptionId($stripeSubscription->id)
            ->setPaymentDetails($paymentDetails);

        return $subscriptionCreation;
    }

    public function stopSubscription(Subscription $subscription): void
    {
        $this->stripe->subscriptions->cancel($subscription->getId());
    }

    public function createCardOnFile(BillingDetails $billingDetails): CardOnFileResponse
    {
        $customerCreation = null;
        if (!$billingDetails->hasCustomerReference()) {
            $customerCreation = $this->setCustomerReference($billingDetails);
        }
        if ($this->config->isPciMode()) {
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
            $cardData = $this->stripe->customers->createSource($billingDetails->getCustomerReference(), $payload);
        } else {
            if (!$billingDetails->getCardDetails()->hasToken()) {
                throw new \Exception('No token');
            }
            $payload = ['source' => $billingDetails->getCardDetails()->getToken()];
            $cardData = $this->stripe->customers->createSource($billingDetails->getCustomerReference(), $payload);
        }

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
        $this->stripe->paymentMethods->detach($cardFile->getStoredPaymentReference());
    }

    public function chargeCardOnFile(Charge $cardFile): ChargeCardResponse
    {
        // TODO add sanity check
        $chargeData = $this->stripe->charges->create(
            [
                'customer' => $cardFile->getBillingDetails()->getCustomerReference(),
                'amount' => $cardFile->getAmount()->getMinorAmount()->toInt(),
                'currency' => $cardFile->getAmount()->getCurrency()->getCurrencyCode(),
                'source' => $cardFile->getBillingDetails()->getStoredPaymentReference(),
                'description' => $cardFile->getName(),
            ]
        );

        $paymentDetails = new PaymentDetails();
        $paymentDetails->setAmount($cardFile->getAmount());
        $paymentDetails->setStoredPaymentReference($cardFile->getBillingDetails()->getStoredPaymentReference());
        $paymentDetails->setPaymentReference($chargeData->id);
        $paymentDetails->setCustomerReference($cardFile->getBillingDetails()->getCustomerReference());

        $chargeCardResponse = new ChargeCardResponse();
        $chargeCardResponse->setPaymentDetails($paymentDetails);

        return $chargeCardResponse;
    }

    public function startFrontendCreateCardOnFile(BillingDetails $billingDetails): FrontendCardProcess
    {
        $customerCreation = null;
        if (!$billingDetails->hasCustomerReference()) {
            $customerCreation = $this->setCustomerReference($billingDetails);
        }
        $intentData = $this->stripe->setupIntents->create(['payment_method_types' => $this->config->getPaymentMethods(), 'customer' => $billingDetails->getCustomerReference()]);

        $process = new FrontendCardProcess();
        $process->setToken($intentData->client_secret);
        $process->setCustomerReference($billingDetails->getCustomerReference());
        $process->setCustomerCreation($customerCreation);

        return $process;
    }

    private function setCustomerReference(BillingDetails $billingDetails): CustomerCreation
    {
        $customer = new Customer();
        $customer->setEmail($billingDetails->getEmail());
        $customer->setName($billingDetails->getName());
        $customer->setAddress($billingDetails->getAddress());

        $customerCreation = $this->provider->customers()->create($customer);

        $billingDetails->setCustomerReference($customerData->id);

        return $customerCreation;
    }
}
