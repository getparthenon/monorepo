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
