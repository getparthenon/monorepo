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

namespace Obol\Tests;

use Obol\Exception\InvalidProviderException;
use Obol\Factory;
use Obol\Provider\Adyen\Provider as AdyenProvider;
use Obol\Provider\ProviderInterface;
use Obol\Provider\Stripe\Provider as StripeProvider;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    public function testNoProvider()
    {
        $this->expectException(InvalidProviderException::class);
        $actual = Factory::create(['provider' => '', 'api_key' => 'test']);
    }

    public function testInvalidProvider()
    {
        $this->expectException(InvalidProviderException::class);
        $actual = Factory::create(['provider' => 'Invalid', 'api_key' => 'test']);
    }

    public function testStripeClient()
    {
        $actual = Factory::create(['provider' => 'stripe', 'api_key' => 'test']);

        $this->assertInstanceOf(ProviderInterface::class, $actual);
        $this->assertInstanceOf(StripeProvider::class, $actual);
    }

    public function testAdyenClient()
    {
        $actual = Factory::create(['provider' => AdyenProvider::NAME, 'api_key' => 'asdt0', 'merchant_account' => 'test', 'return_url' => 'test']);

        $this->assertInstanceOf(AdyenProvider::class, $actual);
        $this->assertInstanceOf(ProviderInterface::class, $actual);
    }
}
