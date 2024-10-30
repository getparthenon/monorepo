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

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Parthenon\Billing\Entity\BillingAdminInterface;
use Parthenon\Billing\Plan\LimitedUserInterface;
use Parthenon\Payments\Subscriber\SubscriberInterface;
use Parthenon\User\Entity\MemberInterface;
use Parthenon\User\Entity\TeamInterface;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User extends \Parthenon\User\Entity\User implements MemberInterface, LimitedUserInterface, BillingAdminInterface
{
    #[ORM\ManyToOne(targetEntity: Team::class, fetch: 'EAGER')]
    private Team|SubscriberInterface $team;

    public function getTeam(): Team
    {
        return $this->team;
    }

    public function hasTeam(): bool
    {
        return isset($this->team);
    }

    /**
     * @param Team $team
     */
    public function setTeam(TeamInterface $team): self
    {
        $this->team = $team;

        return $this;
    }

    public function getPlanName(): ?string
    {
        return $this->team->getSubscription()->getPlanName();
    }

    public function getDisplayName(): string
    {
        return $this->email;
    }
}
