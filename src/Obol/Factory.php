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

namespace Obol;

use Obol\Exception\InvalidProviderException;
use Obol\Provider\Adyen\Factory as AdyenFactory;
use Obol\Provider\ProviderInterface;
use Obol\Provider\Stripe\Factory as StripeFactory;

class Factory
{
    /**
     * @throws Exception\InvalidConfigException
     * @throws InvalidProviderException
     */
    public static function create(array $config): ProviderInterface
    {
        if (!isset($config['provider']) || empty($config['provider']) || !is_string($config['provider'])) {
            throw new InvalidProviderException('No provider given');
        }

        $provider = $config['provider'];

        if ('stripe' === $provider) {
            return StripeFactory::create($config);
        }

        if ('adyen' === $provider) {
            return AdyenFactory::create($config);
        }

        throw new InvalidProviderException(sprintf('Invalid Provider - %s', $config['provider']));
    }
}
