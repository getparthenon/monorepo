<?php

declare(strict_types=1);

/*
 * Copyright (C) 2020-2024 Iain Cambridge
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

use Obol\Exception\InvalidConfigException;
use Obol\Provider\ProviderInterface;
use Psr\Log\LoggerInterface;
use Stripe\StripeClient;

class Factory
{
    public static function create(array $configData, ?LoggerInterface $logger = null): ProviderInterface
    {
        if (!isset($configData['provider']) || Provider::NAME !== strtolower($configData['provider'])) {
            throw new InvalidConfigException('Provider is not defined as stripe');
        }

        $config = static::createConfig($configData);

        $stripe = new StripeClient(
            ['api_key' => $config->getApiKey()],
        );

        $provider = new Provider($config, $stripe);

        if (null !== $logger) {
            $provider->setLogger($logger);
        }

        return $provider;
    }

    protected static function createConfig(array $configData): Config
    {
        $config = new Config();

        if (!isset($configData['api_key']) || empty($configData['api_key']) || !is_string($configData['api_key'])) {
            throw new InvalidConfigException('Api key is not defined');
        }

        if (isset($configData['pci_mode']) && !empty($configData['pci_mode'])) {
            $pciMode = (bool) $configData['pci_mode'];
        } else {
            $pciMode = false;
        }

        $config->setApiKey($configData['api_key'])
            ->setPciMode($pciMode);
        if (isset($configData['payment_methods']) && !empty($configData['payment_methods'])) {
            $config->setPaymentMethods($configData['payment_methods']);
        }
        if (isset($configData['success_url']) && !empty($configData['success_url'])) {
            $config->setSuccessUrl($configData['success_url']);
        }
        if (isset($configData['cancel_url']) && !empty($configData['cancel_url'])) {
            $config->setCancelUrl($configData['cancel_url']);
        }

        return $config;
    }
}
