<?php

declare(strict_types=1);

/*
 * Copyright Iain Cambridge 2020-2022.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: TBD ( 3 years after 2.2.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace App\Tests\Behat\Qa\User;

use Behat\Behat\Context\Context;
use Behat\Mink\Session;

class AthenaContext implements Context
{
    public function __construct(
        private Session $session,
    ) {
    }

    /**
     * @When I click export
     */
    public function iClickExport()
    {
        $this->session->getPage()->clickLink('parthenon_athena_user_gdpr_export');
    }

    /**
     * @Then I should receive a json file that contains in user the field :field with value :value
     */
    public function iShouldReceiveAJsonFileThatContainsInUserTheFieldWithValue($field, $value)
    {
        $rawContent = $this->session->getPage()->getContent();
        $json = json_decode($rawContent, true);
        if (!isset($json['user'][$field]) || $json['user'][$field] !== $value) {
            throw new \Exception("Can't find value");
        }
    }

    /**
     * @When I go to teams athena page
     */
    public function iGoToTeamsAthenaPage()
    {
        $this->session->visit('/athena/team/list');
    }

    /**
     * @Given I go to the team :arg1 page
     */
    public function iGoToTheTeamPage($teamName)
    {
        $this->session->getPage()->clickLink($teamName);
    }
}
