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

namespace Parthenon\Billing\Webhook\Handler;

use Obol\Model\Events\AbstractDispute;
use Obol\Model\Events\EventInterface;
use Parthenon\Billing\ChargeBack\ChargeBackFactoryInterface;
use Parthenon\Billing\Enum\ChargeBackReason;
use Parthenon\Billing\Enum\ChargeBackStatus;
use Parthenon\Billing\Repository\ChargeBackRepositoryInterface;
use Parthenon\Billing\Webhook\HandlerInterface;
use Parthenon\Common\Exception\NoEntityFoundException;

class ChargeBackHandler implements HandlerInterface
{
    public function __construct(
        private ChargeBackRepositoryInterface $chargeBackRepository,
        private ChargeBackFactoryInterface $factory,
    ) {
    }

    public function supports(EventInterface $event): bool
    {
        return $event instanceof AbstractDispute;
    }

    /**
     * @param AbstractDispute $event
     */
    public function handle(EventInterface $event): void
    {
        try {
            $chargeBack = $this->chargeBackRepository->getByExternalReference($event->getId());
        } catch (NoEntityFoundException $e) {
            $chargeBack = $this->factory->buildFromEvent($event);
        }

        $chargeBack->setStatus(ChargeBackStatus::fromName($event->getStatus()));
        $chargeBack->setReason(ChargeBackReason::fromName($event->getReason()));
        $chargeBack->setUpdatedAt(new \DateTime());

        $this->chargeBackRepository->save($chargeBack);
    }
}
