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

namespace App\Tests\Behat\Qa\Athena;

use Behat\Behat\Context\Context;
use Behat\Mink\Session;
use Symfony\Component\HttpFoundation\Response;

class MainContext implements Context
{
    public function __construct(
        private Session $session,
    ) {
    }

    /**
     * @When I go to the Athena main page
     */
    public function iGoToTheAthenaMainPage()
    {
        $this->session->visit('/athena/index');
    }

    /**
     * @When I click export all users
     */
    public function iClickExportAllUsers()
    {
        $this->session->getPage()->pressButton('export_all');
    }

    /**
     * @Then I should have a csv download
     */
    public function iShouldHaveACsvDownloadve()
    {
        $client = $this->session->getDriver()->getClient();

        /** @var Response $response */
        $response = $client->getResponse();

        $header = $response->headers->get('content-disposition');

        if (0 !== strpos($header, 'attachment')) {
            throw new \Exception('Attachment header non-existant');
        }

        $content = $response->getContent();

        $fp = fopen('php://memory', 'w+');
        fwrite($fp, $content);
        fseek($fp, 0);
        $validData = fgetcsv($fp);
        if (false === $validData) {
            throw new \Exception('Not a single valid row');
        }
    }
}
