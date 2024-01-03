<?php

declare(strict_types=1);

/*
 * Copyright Humbly Arrogant Software Limited 2020-2024
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: 26.06.2026 ( 3 years after 2.2.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
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
