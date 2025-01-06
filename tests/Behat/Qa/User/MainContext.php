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

namespace App\Tests\Behat\Qa\User;

use App\Tests\Behat\Skeleton\SendRequestTrait;
use App\Tests\TestKernel;
use Behat\Behat\Context\Context;
use Behat\Mink\Session;

class MainContext implements Context
{
    use SendRequestTrait;

    public function __construct(private Session $session)
    {
    }

    /**
     * @Given email confirmation is enabled for new users
     */
    public function emailConfirmationIsEnabledForNewUsers()
    {
        TestKernel::setParameter('parthenon_user_email_confirmation', true);
    }

    /**
     * @Given email confirmation is disabled for new users
     */
    public function emailConfirmationIsDisabledForNewUsers()
    {
        TestKernel::setParameter('parthenon_user_email_confirmation', false);
    }

    /**
     * @Given logged in after sign up is enabled
     */
    public function loggedInAfterSignUpIsEnabled()
    {
        TestKernel::setParameter('parthenon_user_signed_in_after_signup', true);
    }

    /**
     * @Then the payload will contain the user data
     */
    public function thePayloadWillContainTheUserData()
    {
        $data = $this->getJsonContent();

        if (!isset($data['user'])) {
            throw new \Exception('User data is not set');
        }
    }

    /**
     * @Given logged in after sign up is not enabled
     */
    public function loggedInAfterSignUpIsNotEnabled()
    {
        TestKernel::setParameter('parthenon_user_signed_in_after_signup', false);
    }

    /**
     * @Then the payload will not contain the user data
     */
    public function thePayloadWillNotContainTheUserData()
    {
        $data = $this->getJsonContent();

        if (isset($data['user'])) {
            throw new \Exception('User data is set');
        }
    }
}
