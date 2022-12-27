<?php

declare(strict_types=1);

/*
 * Copyright Iain Cambridge 2020-2022.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: TBD ( 3 years after 2.2.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace Obol\Model;

use Brick\Money\Money;

class Subscription
{
    protected BillingDetails $billingDetails;

    protected Money $costPerSeat;

    protected string $name;

    protected int $seats = 1;

    public function getBillingDetails(): BillingDetails
    {
        return $this->billingDetails;
    }

    public function setBillingDetails(BillingDetails $billingDetails): static
    {
        $this->billingDetails = $billingDetails;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSeats(): int
    {
        return $this->seats;
    }

    public function setSeats(int $seats): static
    {
        $this->seats = $seats;

        return $this;
    }

    public function getCostPerSeat(): Money
    {
        return $this->costPerSeat;
    }

    public function setCostPerSeat(Money $costPerSeat): void
    {
        $this->costPerSeat = $costPerSeat;
    }

    public function getTotalCost(): Money
    {
        return $this->costPerSeat->multipliedBy($this->seats);
    }
}
