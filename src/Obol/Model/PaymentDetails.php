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

class PaymentDetails
{
    protected string $customerReference;

    protected ?string $paymentReference = null;

    protected ?string $paymentDetailsReference = null;

    protected Money $amount;

    public function getCustomerReference(): string
    {
        return $this->customerReference;
    }

    public function setCustomerReference(string $customerReference): static
    {
        $this->customerReference = $customerReference;

        return $this;
    }

    public function getPaymentReference(): ?string
    {
        return $this->paymentReference;
    }

    public function setPaymentReference(?string $paymentReference): static
    {
        $this->paymentReference = $paymentReference;

        return $this;
    }

    public function getPaymentDetailsReference(): ?string
    {
        return $this->paymentDetailsReference;
    }

    public function setPaymentDetailsReference(?string $paymentDetailsReference): static
    {
        $this->paymentDetailsReference = $paymentDetailsReference;

        return $this;
    }

    public function getAmount(): Money
    {
        return $this->amount;
    }

    public function setAmount(Money $amount): static
    {
        $this->amount = $amount;

        return $this;
    }
}
