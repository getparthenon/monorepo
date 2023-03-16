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

use Obol\Exception\InvalidConfigException;
use Obol\Provider\ProviderInterface;
use Stripe\StripeClient;

class Factory
{
    public static function create(array $configData): ProviderInterface
    {
        if (!isset($configData['provider']) || Provider::NAME !== strtolower($configData['provider'])) {
            throw new InvalidConfigException('Provider is not defined as stripe');
        }

        $config = static::createConfig($configData);

        $stripe = new StripeClient(
            ['api_key' => $config->getApiKey()],
        );

        $provider = new Provider($config, $stripe);

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
