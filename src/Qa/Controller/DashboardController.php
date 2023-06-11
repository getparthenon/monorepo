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

namespace App\Qa\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

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
