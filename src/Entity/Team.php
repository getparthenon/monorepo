<?php

declare(strict_types=1);

/*
 * Copyright Humbly Arrogant Ltd 2020-2022.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: TBD ( 3 years after 2.0.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Parthenon\Subscriptions\Entity\Subscription;
use Parthenon\Subscriptions\Subscriber\SubscriberInterface;
use Parthenon\User\Entity\UserInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="teams")
 */
class Team extends \Parthenon\User\Entity\Team implements SubscriberInterface
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="team")
     *
     * @var UserInterface[]|Collection
     */
    protected Collection $members;
    /**
     * @ORM\Embedded(class="Parthenon\Subscriptions\Entity\Subscription")
     */
    private ?Subscription $subscription;

    public function setSubscription(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public function getSubscription(): ?Subscription
    {
        return $this->subscription;
    }

    public function hasActiveSubscription(): bool
    {
        if (!$this->subscription) {
            return false;
        }

        return $this->subscription->isActive();
    }

    public function getIdentifier(): string
    {
        return (string) $this->getName();
    }
}
