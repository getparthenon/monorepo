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

namespace Obol\Tests\Provider\Adyen;

use Obol\Exception\InvalidConfigException;
use Obol\Provider\Adyen\Factory;
use Obol\Provider\Adyen\Provider;
use Obol\Provider\ProviderInterface;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    public function testInvalidProvider()
    {
        $this->expectException(InvalidConfigException::class);

        $actual = Factory::create(['provider' => 'stripe']);
    }

    public function testMissingApiKey()
    {
        $this->expectException(InvalidConfigException::class);

        $actual = Factory::create(['provider' => Provider::NAME]);
    }

    public function testMissingMerchantAccount()
    {
        $this->expectException(InvalidConfigException::class);

        $actual = Factory::create(['provider' => Provider::NAME, 'api_key' => 'asdt0', 'merchant_account' => '']);
    }

    public function testMissingReturnUrl()
    {
        $this->expectException(InvalidConfigException::class);

        $actual = Factory::create(['provider' => Provider::NAME, 'api_key' => 'asdt0', 'merchant_account' => 'test']);
    }

    public function testWorks()
    {
        $actual = Factory::create(['provider' => Provider::NAME, 'api_key' => 'asdt0', 'merchant_account' => 'test', 'return_url' => 'test']);

        $this->assertInstanceOf(Provider::class, $actual);
        $this->assertInstanceOf(ProviderInterface::class, $actual);
    }
}
