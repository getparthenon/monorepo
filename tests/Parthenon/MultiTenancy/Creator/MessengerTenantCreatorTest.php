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

namespace Parthenon\MultiTenancy\Creator;

use Parthenon\MultiTenancy\Entity\Tenant;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\MessageBusInterface;

class MessengerTenantCreatorTest extends TestCase
{
    public function testMessenger()
    {
        $tenant = new Tenant();

        $messengerBus = $this->createMock(MessageBusInterface::class);
        $messengerBus->expects($this->once())->method('dispatch')->with($tenant);

        $subject = new MessengerTenantCreator($messengerBus);
        $subject->createTenant($tenant);
    }
}
