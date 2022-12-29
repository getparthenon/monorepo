<?php

declare(strict_types=1);

/*
 * Copyright Iain Cambridge 2020-2022.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: TBD ( 3 years after 2.2.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace Obol\Provider\Stripe;

use Obol\Model\CheckoutCreation;
use Obol\Model\Subscription;
use Stripe\StripeClient;

class HostedCheckoutService implements \Obol\HostedCheckoutService
{
    protected StripeClient $stripe;

    protected Config $config;

    /**
     * @param StripeClient $stripe
     */
    public function __construct(Config $config, ?StripeClient $stripe = null)
    {
        $this->config = $config;
        $this->stripe = $stripe ?? new StripeClient($this->config->getApiKey());
    }

    public function createCheckoutForSubscription(Subscription $subscription): CheckoutCreation
    {
        $checkoutData = $this->stripe->checkout->sessions->create([
            'mode' => 'subscription',
            'success_url' => $this->config->getSuccessUrl(),
            'cancel_url' => $this->config->getCancelUrl(),
            'line_items' => [
                [
                    'price' => $subscription->getPriceId(),
                    'quantity' => $subscription->getSeats(),
                ],
            ],
            'payment_method_types' => $this->config->getPayments(),
        ]);

        $checkoutCreation = new CheckoutCreation();
        $checkoutCreation->setCheckoutUrl($checkoutData->url);

        return $checkoutCreation;
    }
}
