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

namespace Obol\Model\Invoice;

class InvoiceLine
{
    private string $id;

    private int $amount;

    private string $currency;

    private ?string $mainSubscriptionReference = null;

    private ?string $childSubscriptionReference = null;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    public function getMainSubscriptionReference(): ?string
    {
        return $this->mainSubscriptionReference;
    }

    public function setMainSubscriptionReference(?string $mainSubscriptionReference): void
    {
        $this->mainSubscriptionReference = $mainSubscriptionReference;
    }

    public function getChildSubscriptionReference(): ?string
    {
        return $this->childSubscriptionReference;
    }

    public function setChildSubscriptionReference(?string $childSubscriptionReference): void
    {
        $this->childSubscriptionReference = $childSubscriptionReference;
    }

    public function hasReferences(): bool
    {
        return isset($this->mainSubscriptionReference) && isset($this->childSubscriptionReference);
    }
}
