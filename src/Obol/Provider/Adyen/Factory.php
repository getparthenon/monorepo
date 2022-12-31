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

namespace Obol\Provider\Adyen;

use Obol\Exception\InvalidConfigException;
use Obol\Provider\ProviderInterface;

class Factory
{
    public static function create(array $configData): ProviderInterface
    {
        if (!isset($configData['provider']) || Provider::NAME !== strtolower($configData['provider'])) {
            throw new InvalidConfigException('Provider is not defined as adyen');
        }
        $config = static::createConfig($configData);

        $provider = new Provider(
            new PaymentService($config),
            new HostedCheckoutService($config),
        );

        return $provider;
    }

    protected static function createConfig(array $configData): Config
    {
        $config = new Config();

        if (!isset($configData['api_key']) || empty($configData['api_key']) || !is_string($configData['api_key'])) {
            throw new InvalidConfigException('Api key is not defined');
        }
        if (!isset($configData['merchant_account']) || empty($configData['merchant_account']) || !is_string($configData['merchant_account'])) {
            throw new InvalidConfigException('Merchant account is not defined');
        }
        if (!isset($configData['return_url']) || empty($configData['return_url']) || !is_string($configData['return_url'])) {
            throw new InvalidConfigException('Return url is not defined');
        }

        if (isset($configData['pci_mode']) && !empty($configData['pci_mode'])) {
            $pciMode = (bool) $configData['pci_mode'];
        } else {
            $pciMode = false;
        }
        if (isset($configData['test_mode']) && !empty($configData['test_mode'])) {
            $testMode = (bool) $configData['test_mode'];
        } else {
            $testMode = true;
        }

        $config->setApiKey($configData['api_key'])
            ->setPciMode($pciMode)
            ->setTestMode($testMode)
            ->setMerchantAccount($configData['api_key'])
            ->setReturnUrl($configData['return_url']);

        if (isset($configData['prefix']) && !empty($configData['prefix'])) {
            $config->setPrefix($configData['prefix']);
        } elseif (!$testMode) {
            throw new InvalidConfigException('Prefix is not defined and not set as test mode');
        }

        return $config;
    }
}
