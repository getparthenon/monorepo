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

namespace Obol\Tests\Provider\Stripe;

use Obol\Exception\InvalidConfigException;
use Obol\Provider\ProviderInterface;
use Obol\Provider\Stripe\Factory;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    public function testInvalidProvider()
    {
        $this->expectException(InvalidConfigException::class);

        Factory::create([]);
    }

    public function testInvalidProviderApiKeyEmptyString()
    {
        $this->expectException(InvalidConfigException::class);

        Factory::create(['provider' => 'stripe', 'api_key' => '']);
    }

    public function testInvalidProviderApiKeyNotString()
    {
        $this->expectException(InvalidConfigException::class);

        Factory::create(['provider' => 'stripe', 'api_key' => true]);
    }

    public function testValid()
    {
        $actual = Factory::create(['provider' => 'stripe', 'api_key' => 'test']);

        $this->assertInstanceOf(ProviderInterface::class, $actual);
    }
}
