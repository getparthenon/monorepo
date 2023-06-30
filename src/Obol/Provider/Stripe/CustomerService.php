<?php

declare(strict_types=1);

/*
 * Copyright Humbly Arrogant Software Limited 2020-2023.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: 26.06.2026 ( 3 years after 2.2.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace Obol\Provider\Stripe;

use Obol\Exception\ProviderFailureException;
use Obol\Model\Address;
use Obol\Model\Customer;
use Obol\Model\CustomerCreation;
use Obol\Model\PaymentMethod\PaymentMethodCard;
use Obol\Provider\ProviderInterface;
use Stripe\StripeClient;

class CustomerService implements \Obol\CustomerServiceInterface
{
    protected StripeClient $stripe;

    protected Config $config;

    /**
     * @param StripeClient $stripe
     */
    public function __construct(private ProviderInterface $provider, Config $config, ?StripeClient $stripe = null)
    {
        $this->config = $config;
        $this->stripe = $stripe ?? new StripeClient($this->config->getApiKey());
    }

    public function create(Customer $customer): CustomerCreation
    {
        try {
            $customerData = $this->stripe->customers->create(
                [
                    'address' => [
                        'city' => $customer->getAddress()->getCity(),
                        'country' => $customer->getAddress()->getCountryCode(),
                        'line1' => $customer->getAddress()->getStreetLineOne(),
                        'line2' => $customer->getAddress()->getStreetLineTwo(),
                        'postal_code' => $customer->getAddress()->getPostalCode(),
                        'state' => $customer->getAddress()->getState(),
                    ],
                    'description' => $customer->getDescription(),
                    'email' => $customer->getEmail(),
                    'name' => $customer->getName(),
                ]
            );
        } catch (\Throwable $exception) {
            throw new ProviderFailureException(previous: $e);
        }

        if (true === $customerData->livemode) {
            $url = sprintf('https://dashboard.stripe.com/customers/%s', $customerData->id);
        } else {
            $url = sprintf('https://dashboard.stripe.com/test/customers/%s', $customerData->id);
        }

        $customerCreation = new CustomerCreation();
        $customerCreation->setReference($customerData->id);
        $customerCreation->setDetailsUrl($url);

        return $customerCreation;
    }

    public function fetch(string $customerId): Customer
    {
        $stripeCustomer = $this->stripe->customers->retrieve($customerId);

        return $this->populateCustomer($stripeCustomer);
    }

    public function list(int $limit = 10, ?string $lastId = null): array
    {
        $payload = ['limit' => $limit];
        if (isset($lastId) && !empty($lastId)) {
            $payload['starting_after'] = $lastId;
        }
        $result = $this->stripe->customers->all($payload);
        $output = [];
        foreach ($result->data as $stripeCustomer) {
            $output[] = $this->populateCustomer($stripeCustomer);
        }

        return $output;
    }

    public function getCards(string $customerId, int $limit = 10, ?string $lastId = null): array
    {
        $payload = ['limit' => $limit];
        if (isset($lastId) && !empty($lastId)) {
            $payload['starting_after'] = $lastId;
        }

        $result = $this->stripe->customers->allPaymentMethods($customerId, $payload);
        $output = [];
        foreach ($result->data as $stripePayment) {
            if (!isset($stripePayment->card)) {
                continue;
            }
            $paymentMethodCard = new PaymentMethodCard();
            $paymentMethodCard->setId($stripePayment->id);
            $paymentMethodCard->setCustomerReference($stripePayment->customer);
            $paymentMethodCard->setLastFour($stripePayment->card->last4);
            $paymentMethodCard->setExpiryMonth((string) $stripePayment->card->exp_month);
            $paymentMethodCard->setExpiryYear((string) $stripePayment->card->exp_year);
            $paymentMethodCard->setBrand($stripePayment->card->brand);

            $createdAt = new \DateTime();
            $paymentMethodCard->setCreatedAt($createdAt);
            $output[] = $paymentMethodCard;
        }

        return $output;
    }

    private function populateCustomer(\Stripe\Customer $stripeCustomer): Customer
    {
        if (true === $stripeCustomer->livemode) {
            $url = sprintf('https://dashboard.stripe.com/customers/%s', $stripeCustomer->id);
        } else {
            $url = sprintf('https://dashboard.stripe.com/test/customers/%s', $stripeCustomer->id);
        }
        $customer = new Customer();
        $customer->setId($stripeCustomer->id);
        $customer->setName($stripeCustomer->name);
        $customer->setEmail($stripeCustomer->email);
        $customer->setDescription($stripeCustomer->description);
        $customer->setUrl($url);
        $customer->setDefaultSource($stripeCustomer->default_source);

        $address = new Address();
        $address->setStreetLineOne($stripeCustomer->address?->line1);
        $address->setStreetLineTwo($stripeCustomer->address?->line2);
        $address->setCity($stripeCustomer->address?->city);
        $address->setCountryCode($stripeCustomer->address?->country);
        $address->setState($stripeCustomer->address?->state);
        $address->setPostalCode($stripeCustomer->address?->postal_code);
        $customer->setAddress($address);

        $createdAt = new \DateTime();
        $createdAt->setTimestamp($stripeCustomer->created);
        $customer->setCreatedAt($createdAt);

        return $customer;
    }
}
