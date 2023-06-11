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
