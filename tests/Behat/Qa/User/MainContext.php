<?php

declare(strict_types=1);

/*
 * Copyright Iain Cambridge 2020-2022.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: TBD ( 3 years after 2.1.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace App\Tests\Behat\Qa\User;

use App\Tests\TestKernel;
use Behat\Behat\Context\Context;

class MainContext implements Context
{
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
}
