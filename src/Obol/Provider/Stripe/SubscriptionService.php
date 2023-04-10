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

use Obol\Model\Subscription\UpdatePaymentMethod;
use Obol\Provider\ProviderInterface;
use Obol\SubscriptionServiceInterface;
use Stripe\StripeClient;

class SubscriptionService implements SubscriptionServiceInterface
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

    public function updatePaymentMethod(UpdatePaymentMethod $updatePaymentMethod): void
    {
        $this->stripe->subscriptions->update($updatePaymentMethod->getSubscriptionId(), ['default_payment_method' => $updatePaymentMethod->getPaymentMethodReference()]);
    }
}
