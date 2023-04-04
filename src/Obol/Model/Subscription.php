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

class Subscription
{
    protected string $id;

    protected BillingDetails $billingDetails;

    protected Money $costPerSeat;

    protected string $name;

    protected int $seats = 1;

    protected string $priceId;

    protected ?bool $hasTrial = null;

    protected ?int $trialLengthDays = null;

    protected ?string $parentReference = null;

    protected ?string $storedPaymentReference = null;

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

    public function hasTrial(): ?bool
    {
        return $this->hasTrial;
    }

    public function setHasTrial(?bool $hasTrial): void
    {
        $this->hasTrial = $hasTrial;
    }

    public function getTrialLengthDays(): ?int
    {
        return $this->trialLengthDays;
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
}
