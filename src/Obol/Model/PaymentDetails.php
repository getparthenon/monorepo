<?php

declare(strict_types=1);

/*
 * Copyright (C) 2020-2024 Iain Cambridge
 *
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Obol\Model;

use Brick\Money\Money;

class PaymentDetails
{
    protected ?string $customerReference = null;

    protected ?string $storedPaymentReference = null;

    protected ?string $paymentReference = null;

    protected ?string $paymentReferenceLink = null;

    protected ?string $invoiceReference = null;

    protected Money $amount;

    protected ?\DateTimeInterface $createdAt = null;

    public function getCustomerReference(): ?string
    {
        return $this->customerReference;
    }

    public function setCustomerReference(?string $customerReference): static
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

    public function getInvoiceReference(): ?string
    {
        return $this->invoiceReference;
    }

    public function setInvoiceReference(?string $invoiceReference): void
    {
        $this->invoiceReference = $invoiceReference;
    }
}
