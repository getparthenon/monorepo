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
