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

class Subscription
{
    protected string $id;

    protected ?string $lineId = null;

    protected BillingDetails $billingDetails;

    protected Money $costPerSeat;

    protected string $name;

    protected int $seats = 1;

    protected string $priceId;

    protected ?bool $hasTrial = null;

    protected ?int $trialLengthDays = null;

    protected ?string $parentReference = null;

    protected ?string $storedPaymentReference = null;

    protected ?string $customerReference = null;

    protected ?\DateTimeInterface $createdAt = null;

    protected ?\DateTimeInterface $cancelledAt = null;

    protected ?\DateTimeInterface $validUntil = null;

    protected ?\DateTimeInterface $startOfCurrentPeriod = null;

    private string $status;

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

    public function setCostPerSeat(Money $costPerSeat): static
    {
        $this->costPerSeat = $costPerSeat;

        return $this;
    }

    public function getTotalCost(): Money
    {
        return $this->costPerSeat->multipliedBy($this->seats);
    }

    public function getPriceId(): string
    {
        return $this->priceId;
    }

    public function setPriceId(string $priceId): static
    {
        $this->priceId = $priceId;

        return $this;
    }

    public function hasPriceId(): bool
    {
        return isset($this->priceId);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function hasTrial(): bool
    {
        return true === $this->hasTrial;
    }

    public function setHasTrial(?bool $hasTrial): void
    {
        $this->hasTrial = $hasTrial;
    }

    public function getTrialLengthDays(): int
    {
        return (int) $this->trialLengthDays;
    }

    public function setTrialLengthDays(?int $trialLengthDays): void
    {
        $this->trialLengthDays = $trialLengthDays;
    }

    public function getParentReference(): ?string
    {
        return $this->parentReference;
    }

    public function setParentReference(?string $parentReference): void
    {
        $this->parentReference = $parentReference;
    }

    public function hasStoredPaymentReference(): bool
    {
        return isset($this->storedPaymentReference);
    }

    public function getStoredPaymentReference(): ?string
    {
        return $this->storedPaymentReference;
    }

    public function setStoredPaymentReference(?string $storedPaymentReference): void
    {
        $this->storedPaymentReference = $storedPaymentReference;
    }

    public function getLineId(): ?string
    {
        return $this->lineId;
    }

    public function setLineId(?string $lineId): void
    {
        $this->lineId = $lineId;
    }

    public function hasLineId(): bool
    {
        return isset($this->lineId);
    }

    public function getCustomerReference(): ?string
    {
        return $this->customerReference;
    }

    public function setCustomerReference(?string $customerReference): void
    {
        $this->customerReference = $customerReference;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getValidUntil(): ?\DateTimeInterface
    {
        return $this->validUntil;
    }

    public function setValidUntil(?\DateTimeInterface $validUntil): void
    {
        $this->validUntil = $validUntil;
    }

    public function getStartOfCurrentPeriod(): ?\DateTimeInterface
    {
        return $this->startOfCurrentPeriod;
    }

    public function setStartOfCurrentPeriod(?\DateTimeInterface $startOfCurrentPeriod): void
    {
        $this->startOfCurrentPeriod = $startOfCurrentPeriod;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getCancelledAt(): ?\DateTimeInterface
    {
        return $this->cancelledAt;
    }

    public function setCancelledAt(?\DateTimeInterface $cancelledAt): void
    {
        $this->cancelledAt = $cancelledAt;
    }
}
