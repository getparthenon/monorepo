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

namespace App\User\Entity;

use Parthenon\User\Entity\ForgotPasswordCode;
use Parthenon\User\Entity\InviteCode;
use Parthenon\User\Entity\TeamInterface;
use Parthenon\User\Entity\TeamInviteCode;
use Parthenon\User\Entity\UserInterface;
use Parthenon\User\Factory\EntityFactory as BaseFactory;

class EntityFactory extends BaseFactory
{
    public function buildPasswordReset(UserInterface $user): ForgotPasswordCode
    {
        return \App\Entity\ForgotPasswordCode::createForUser($user);
    }

    public function buildInviteCode(UserInterface $user, string $email): InviteCode
    {
        return \App\Entity\InviteCode::createForUser($user, $email);
    }

    public function buildTeamInviteCode(UserInterface $user, TeamInterface $team, string $email, string $role): TeamInviteCode
    {
        return \App\Entity\TeamInviteCode::createForUserAndTeam($user, $team, $email, $role);
    }
}
