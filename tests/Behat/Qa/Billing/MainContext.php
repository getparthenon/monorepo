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

namespace App\Tests\Behat\Qa\Billing;

use App\Repository\Orm\TeamRepository;
use App\Repository\Orm\UserRepository;
use App\Tests\Behat\Skeleton\SendRequestTrait;
use App\Tests\Behat\Skeleton\TeamTrait;
use App\Tests\Behat\Skeleton\UserTrait;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Session;

class MainContext implements Context
{
    use SendRequestTrait;
    use UserTrait;
    use TeamTrait;

    public function __construct(
        private Session $session,
        private UserRepository $userRepository,
        private TeamRepository $teamRepository,
    ) {
    }

    /**
     * @When I set my billing address as:
     */
    public function iSetMyBillingAddressAs(TableNode $table)
    {
        $sendData = [];

        foreach ($table->getRowsHash() as $key => $value) {
            $key = strtolower($key);
            $key = implode('_', explode(' ', $key));
            $sendData[$key] = $value;
        }

        $this->sendJsonRequest('POST', '/api/billing/address', $sendData);
    }

    /**
     * @Then the customer for :arg1 billing address should have a street line one as :arg2
     */
    public function theCustomerForBillingAddressShouldHaveAStreetLineOneAs($teamName, $streetLine)
    {
        $team = $this->getTeamByName($teamName);

        if ($team->getBillingAddress()->getStreetLineOne() !== $streetLine) {
            throw new \Exception("Street line doesn't match");
        }
    }
}
