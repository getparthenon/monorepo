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

namespace App\Qa\Controller;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController
{
    #[Route('/api/qa/dashboard', name: 'app_qa_dashboard')]
    public function dashboard(Security $security)
    {
        /** @var User $user */
        $user = $security->getUser();

        return new JsonResponse(
            ['subscription' => [
                'plan' => $user->getTeam()?->getSubscription()?->getPlanName(),
                'status' => $user->getTeam()?->getSubscription()?->getStatus(),
            ]]
        );
    }
}
