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

namespace Obol\Model;

use Brick\Money\Money;

class PaymentDetails
{
    protected string $customerReference;

    protected ?string $storedPaymentReference = null;

    protected ?string $paymentReference = null;

    protected ?string $paymentReferenceLink = null;

    protected Money $amount;

    protected \DateTimeInterface $createdAt = null;

    public function getCustomerReference(): string
    {
        return $this->customerReference;
    }

    public function setCustomerReference(string $customerReference): static
    {
        $this->customerReference = $customerReference;

        return $this;
    }

    public function getStoredPaymentReference(): ?string
    {
        return $this->storedPaymentReference;
    }

    public function setStoredPaymentReference(?string $storedPaymentReference): static
    {
        $this->storedPaymentReference = $storedPaymentReference;

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

    public function getAmount(): Money
    {
        return $this->amount;
    }

    public function setAmount(Money $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getPaymentReferenceLink(): ?string
    {
        return $this->paymentReferenceLink;
    }

    public function setPaymentReferenceLink(?string $paymentReferenceLink): void
    {
        $this->paymentReferenceLink = $paymentReferenceLink;
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
