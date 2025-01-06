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

namespace Obol;

use Obol\Exception\InvalidProviderException;
use Obol\Provider\Adyen\Factory as AdyenFactory;
use Obol\Provider\ProviderInterface;
use Obol\Provider\Stripe\Factory as StripeFactory;
use Psr\Log\LoggerInterface;

class Factory
{
    /**
     * @throws Exception\InvalidConfigException
     * @throws InvalidProviderException
     */
    public static function create(array $config, ?LoggerInterface $logger = null): ProviderInterface
    {
        if (!isset($config['provider']) || empty($config['provider']) || !is_string($config['provider'])) {
            throw new InvalidProviderException('No provider given');
        }

        $provider = $config['provider'];

        if ('stripe' === $provider) {
            return StripeFactory::create($config, $logger);
        }

        if ('adyen' === $provider) {
            return AdyenFactory::create($config);
        }

        throw new InvalidProviderException(sprintf('Invalid Provider - %s', $config['provider']));
    }
}
