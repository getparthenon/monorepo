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

namespace Obol\Provider\TransactionCloud;

use Obol\Model\CheckoutCreation;
use Obol\Model\Subscription;

class HostedCheckoutService implements \Obol\HostedCheckoutService
{
    protected Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function createCheckoutForSubscription(Subscription $subscription): CheckoutCreation
    {
        $url = sprintf('%s/payment/product/%s', $this->config->getDefaultUrl(), $subscription->getPriceId());

        $checkoutCreation = new CheckoutCreation();
        $checkoutCreation->setCheckoutUrl($url);

        return $checkoutCreation;
    }
}
