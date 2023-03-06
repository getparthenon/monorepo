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

namespace Parthenon\Billing\Controller;

use Parthenon\Billing\Plan\Plan;
use Parthenon\Billing\Plan\PlanManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PlanController
{
    #[Route('/billing/plans', name: 'parthenon_billing_plan_list')]
    public function listAction(PlanManager $planManager)
    {
        $plans = $planManager->getPlans();

        $output = [];

        foreach ($plans as $plan) {
            $output[$plan->getName()] = [
                'name' => $plan->getName(),
                'limits' => $plan->getLimits(),
                'features' => $plan->getFeatures(),
                'prices' => $this->generateSchedules($plan),
            ];
        }

        return new JsonResponse(['plans' => $output]);
    }

    private function generateSchedules(Plan $plan): array
    {
        $output = [];

        foreach ($plan->getPrices() as $data) {
            $output[] = [
                'schedule' => $data->getSchedule(),
                'amount' => $data->getPriceAsMoney()->getAmount(),
                'currency' => $data->getCurrency(),
            ];
        }

        return $output;
    }
}
