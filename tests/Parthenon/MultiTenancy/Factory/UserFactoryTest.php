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

namespace Parthenon\MultiTenancy\Factory;

use Parthenon\MultiTenancy\Model\SignUp;
use Parthenon\User\Entity\User;
use PHPUnit\Framework\TestCase;

class UserFactoryTest extends TestCase
{
    public function testReturnsUser()
    {
        $userFactory = new UserFactory(new User());

        $signUp = new SignUp();
        $signUp->setEmail('user@example.org');
        $signUp->setPassword('a-fake-password');

        $user = $userFactory->buildUserFromSignUp($signUp);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('user@example.org', $user->getEmail());
        $this->assertEquals('a-fake-password', $user->getPassword());
    }
}
