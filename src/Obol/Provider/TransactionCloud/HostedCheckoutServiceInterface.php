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

namespace Obol\Provider\TransactionCloud;

use Obol\Model\CheckoutCreation;
use Obol\Model\Subscription;

class HostedCheckoutServiceInterface implements \Obol\HostedCheckoutServiceInterface
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
