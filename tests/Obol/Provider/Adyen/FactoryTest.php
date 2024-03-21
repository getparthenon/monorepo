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
