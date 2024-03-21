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

namespace App\Tests\Behat\Skeleton;

use Behat\Behat\Context\Context;
use Behat\Mink\Session;
use Parthenon\Billing\Plan\PlanManager;

class PlanContext implements Context
{
    use SendRequestTrait;

    public function __construct(private Session $session, private PlanManager $planManager)
    {
    }

    /**
     * @When I view the plans
     */
    public function iViewThePlans()
    {
        $this->sendJsonRequest('GET', '/api/billing/plans');
    }

    /**
     * @Then I should see the plans that are configured
     */
    public function iShouldSeeThePlansThatAreConfigured()
    {
        $content = $this->getJsonContent();

        $plans = $this->planManager->getPlans();

        foreach ($plans as $plan) {
            $name = $plan->getName();
            if (!isset($content['plans'][$name])) {
                throw new \Exception("Can't see plan ".$name);
            }

            if ($content['plans'][$name]['limits'] != $plan->getLimits()) {
                throw new \Exception('Plan for '.$plan->getLimits());
            }
        }
    }
}
