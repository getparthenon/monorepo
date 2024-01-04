<?php

declare(strict_types=1);

/*
 * Copyright Humbly Arrogant Software Limited 2020-2024
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: 26.06.2026 ( 3 years after 2.2.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace Obol\Provider\Stripe;

use Obol\Exception\ProviderFailureException;
use Obol\Model\CheckoutCreation;
use Obol\Model\Subscription;
use Obol\Provider\ProviderInterface;
use Stripe\StripeClient;

class HostedCheckoutService implements \Obol\HostedCheckoutServiceInterface
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

    public function createCheckoutForSubscription(Subscription $subscription): CheckoutCreation
    {
        $this->stripe->billingPortal->configurations->all();

        try {
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
                'payment_method_types' => $this->config->getPaymentMethods(),
            ]);
        } catch (\Throwable $e) {
            throw new ProviderFailureException(previous: $e);
        }

        $checkoutCreation = new CheckoutCreation();
        $checkoutCreation->setCheckoutUrl($checkoutData->url);

        return $checkoutCreation;
    }
}
