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

namespace Parthenon\Payments\Entity;

use Parthenon\Payments\Subscriber\SubscriberInterface;

class SubscriptionEvent
{
    public const TYPE_CREATED = 'created';
    public const TYPE_CHANGE_BEFORE = 'change_before';
    public const TYPE_CHANGE_AFTER = 'change_after';
    public const TYPE_CANCELLED = 'cancelled';
    public const TYPE_PAYMENT = 'payment';

    protected $id;

    protected SubscriberInterface $subscriber;

    protected string $type;

    protected string $planName;

    protected array $data;
}
