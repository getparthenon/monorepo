<?php

declare(strict_types=1);

/*
 * Copyright Iain Cambridge 2020-2022.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: 16.12.2025
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace App\Repository;

use App\Entity\InviteCode;
use App\Entity\Team;

interface InviteCodeRepositoryInterface extends \Parthenon\User\Repository\InviteCodeRepositoryInterface
{
    /**
     * @return InviteCode[]
     */
    public function findAllUnusedInvitesForTeam(Team $team): array;

    /**
     * @return InviteCode[]
     */
    public function getUsableInviteCount(Team $team): int;
}
