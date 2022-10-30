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

namespace App\Tests\Behat\Skeleton;

use App\Entity\Team;

trait TeamTrait
{
    protected function getTeamByName(string $name): Team
    {
        $team = $this->teamRepository->findOneBy(['name' => $name]);

        if (!$team instanceof Team) {
            throw new \Exception("Can't find team");
        }

        $this->teamRepository->getEntityManager()->refresh($team);

        return $team;
    }
}
