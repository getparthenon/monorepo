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

namespace App\Qa\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class AbTestingController
{
    #[Route('/qa/landingpage', name: 'app_qa_landing_page')]
    public function landingPage(Environment $environment)
    {
        return new Response($environment->render('qa/abtesting/landingpage.html.twig'));
    }
}
