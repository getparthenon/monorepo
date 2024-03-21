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

use App\Repository\Orm\TeamRepository;
use App\Tests\Behat\Skeleton\SendRequestTrait;
use App\Tests\Behat\Skeleton\TeamTrait;
use Behat\Behat\Context\Context;
use Behat\Mink\Session;

class AthenaContext implements Context
{
    use SendRequestTrait;
    use TeamTrait;

    public function __construct(private Session $session, private TeamRepository $teamRepository)
    {
    }

    /**
     * @When I go to the Athena subscription page
     */
    public function iGoToTheAthenaSubscriptionPage()
    {
        $this->session->visit('/athena/team/list');
    }

    /**
     * @When I view the subscription for :arg1
     */
    public function iViewTheSubscriptionFor($teamName)
    {
        $this->session->getPage()->clickLink($teamName);
    }

    /**
     * @Given I go edit the subscription
     */
    public function iGoEditTheSubscription()
    {
        $this->session->getPage()->clickLink('Edit');
    }

    /**
     * @When I update the plan to :arg1
     */
    public function iUpdateThePlanTo($newPlan)
    {
        $this->session->getPage()->selectFieldOption('athena[subscription][planName]', $newPlan);
        $this->session->getPage()->pressButton('crud_edit_submit');
    }

    /**
     * @Then the plan for :arg1 should be :arg2
     */
    public function thePlanForShouldBe($teamName, $planName)
    {
        $team = $this->getTeamByName($teamName);

        if ($team->getSubscription()->getPlanName() !== $planName) {
            throw new \Exception('Team subscription is '.$team->getSubscription()->getPlanName());
        }
    }
}
