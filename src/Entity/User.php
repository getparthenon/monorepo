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

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Parthenon\Payments\Plan\LimitedUserInterface;
use Parthenon\Payments\Subscriber\SubscriberInterface;
use Parthenon\User\Entity\MemberInterface;
use Parthenon\User\Entity\TeamInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="users")
 */
class User extends \Parthenon\User\Entity\User implements MemberInterface, LimitedUserInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", fetch="EAGER")
     */
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
}
