<?php

declare(strict_types=1);

/*
 * Copyright (C) 2020-2024 Iain Cambridge
 *
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <https://www.gnu.org/licenses/>.
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
