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

use Obol\Model\Customer;
use Obol\Model\CustomerCreation;
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
}
