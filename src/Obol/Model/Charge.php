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

class Charge
{
    protected string $name;

    protected Money $amount;

    protected BillingDetails $billingDetails;

    public function getAmount(): Money
    {
        return $this->amount;
    }

    public function setAmount(Money $amount): void
    {
        $this->amount = $amount;
    }

    public function getBillingDetails(): BillingDetails
    {
        return $this->billingDetails;
    }

    public function setBillingDetails(BillingDetails $billingDetails): void
    {
        $this->billingDetails = $billingDetails;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
