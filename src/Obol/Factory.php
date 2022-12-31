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
