<?php

declare(strict_types=1);

/*
 * Copyright (C) 2020-2024 Iain Cambridge
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

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
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
