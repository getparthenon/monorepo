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

namespace Obol\Model\Events;

class DisputeClosed
{
    private string $disputedPaymentId;

    private string $status;

    private \DateTimeInterface $createdAt;

    public function getDisputedPaymentId(): string
    {
        return $this->disputedPaymentId;
    }

    public function setDisputedPaymentId(string $disputedPaymentId): void
    {
        $this->disputedPaymentId = $disputedPaymentId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
