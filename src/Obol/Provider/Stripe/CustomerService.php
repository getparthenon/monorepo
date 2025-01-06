<?php

declare(strict_types=1);

/*
 * Copyright (C) 2020-2025 Iain Cambridge
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

use Obol\Exception\ProviderFailureException;
use Obol\Model\Address;
use Obol\Model\Customer;
use Obol\Model\CustomerCreation;
use Obol\Model\PaymentMethod\PaymentMethodCard;
use Obol\Provider\ProviderInterface;
use Psr\Log\LoggerAwareTrait;
use Stripe\StripeClient;

class CustomerService implements \Obol\CustomerServiceInterface
{
    use LoggerAwareTrait;

    protected StripeClient $stripe;

    protected Config $config;

    public function __construct(private ProviderInterface $provider, Config $config, ?StripeClient $stripe = null)
    {
        $this->config = $config;
        $this->stripe = $stripe ?? new StripeClient($this->config->getApiKey());
    }

    public function create(Customer $customer): CustomerCreation
    {
        try {
            $customerData = $this->stripe->customers->create(
                $this->generatePayload($customer)
            );
        } catch (\Throwable $exception) {
            throw new ProviderFailureException(sprintf('Got - %s', $exception->getMessage()), previous: $exception);
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

    public function update(Customer $customer): bool
    {
        try {
            $customerData = $this->stripe->customers->update($customer->getId(),
                $this->generatePayload($customer)
            );
        } catch (\Throwable $exception) {
            throw new ProviderFailureException(previous: $exception);
        }

        return true;
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

    public function generatePayload(Customer $customer): array
    {
        return [
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
            'tax_exempt' => $customer->isTaxExempt() ? 'exempt' : 'none',
        ];
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
