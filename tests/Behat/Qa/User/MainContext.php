<?php

declare(strict_types=1);

/*
 * Copyright Humbly Arrogant Software Limited 2020-2023.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: TBD ( 3 years after 2.2.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
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
