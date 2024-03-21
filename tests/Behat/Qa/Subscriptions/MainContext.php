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

namespace App\Tests\Behat\Qa\Subscriptions;

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
     * @Given the Plan :arg1 does not have the feature :arg2
     */
    public function thePlanDoesNotHaveTheFeature($arg1, $arg2)
    {
        $parameters = TestKernel::getParameters();
        TestKernel::setParameter('parthenon_billing_plan_plans', [$arg1 => ['limit' => [], 'features' => []]]);
    }

    /**
     * @When I try to list items
     */
    public function iTryToListItems()
    {
        $this->sendJsonRequest('GET', '/api/qa/subscriptions/list');
    }

    /**
     * @Then I will be refused
     */
    public function iWillBeRefused()
    {
        $data = $this->getJsonContent();

        if ($data['list_features']) {
            throw new \Exception('Listed features');
        }
    }

    /**
     * @Given the Plan :arg1 does have the feature :arg2
     */
    public function thePlanDoesHaveTheFeature($arg1, $arg2)
    {
        TestKernel::setParameter('parthenon_billing_plan_plans', [$arg1 => ['limit' => [], 'features' => [$arg2]]]);
    }

    /**
     * @Then the items will be listed
     */
    public function theItemsWillBeListed()
    {
        $data = $this->getJsonContent();
        if (!$data['list_features']) {
            throw new \Exception('Have not listed features');
        }
    }
}
