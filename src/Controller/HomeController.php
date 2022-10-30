<?php

declare(strict_types=1);

/*
 * Copyright Iain Cambridge 2020-2022.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: 01-10-2025 ( 3 years after 2.0.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HomeController
{
    #[Route('/', name: 'app_index_landing', requirements: ['reactRouting' => '.+'], defaults: ['reactRouting' => null])]
    #[Route('/app/{reactRouting}', name: 'app_index', requirements: ['reactRouting' => '.+'], defaults: ['reactRouting' => null])]
    #[Route('/login', name: 'app_index_login', requirements: ['reactRouting' => '.+'], defaults: ['reactRouting' => null])]
    #[Route('/signup', name: 'app_index_signup', requirements: ['reactRouting' => '.+'], defaults: ['reactRouting' => null])]
    #[Route('/signup/{reactRouting}', name: 'app_index_signup_invite', requirements: ['reactRouting' => '.+'], defaults: ['reactRouting' => null])]
    #[Route('/forgot-password', name: 'app_index_forgot_password', requirements: ['reactRouting' => '.+'], defaults: ['reactRouting' => null])]
    #[Route('/forgot-password/{reactRouting}', name: 'app_index_forgot_password_confirm', requirements: ['reactRouting' => '.+'], defaults: ['reactRouting' => null])]
    #[Route('/confirm-email/{reactRouting}', name: 'app_index_confim', requirements: ['reactRouting' => '.+'], defaults: ['reactRouting' => null])]
    public function indexAction(Environment $twig)
    {
        return new Response($twig->render('default/index.html.twig'));
    }
}
