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

namespace Parthenon\User\Gdpr\Deletion;

use Parthenon\User\Entity\UserInterface;

final class Decider implements VoterInterface
{
    /**
     * @var VoterInterface[]
     */
    private array $voters = [];

    public function add(VoterInterface $voter): self
    {
        $this->voters[] = $voter;

        return $this;
    }

    public function canDelete(UserInterface $user): bool
    {
        foreach ($this->voters as $voter) {
            if (!$voter->canDelete($user)) {
                return false;
            }
        }

        return true;
    }
}
