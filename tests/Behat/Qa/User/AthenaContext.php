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
