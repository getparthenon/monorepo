<?php

declare(strict_types=1);

/*
 * Copyright (C) 2020-2024 Iain Cambridge
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU LESSER GENERAL PUBLIC LICENSE as published by
 * the Free Software Foundation, either version 2.1 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Obol\Model;

class SubscriptionCreationResponse
{
    protected ?string $detailsUrl = null;
    protected string $subscriptionId;

    protected string $lineId;

    protected \DateTimeInterface $billedUntil;

    protected ?PaymentDetails $paymentDetails = null;

    protected ?CustomerCreation $customerCreation = null;

    public function getPaymentDetails(): ?PaymentDetails
    {
        return $this->paymentDetails;
    }

    public function setPaymentDetails(PaymentDetails $paymentDetails): static
    {
        $this->paymentDetails = $paymentDetails;

        return $this;
    }

    public function getSubscriptionId(): string
    {
        return $this->subscriptionId;
    }

    public function setSubscriptionId(string $subscriptionId): static
    {
        $this->subscriptionId = $subscriptionId;

        return $this;
    }

    public function getLineId(): string
    {
        return $this->lineId;
    }

    public function setLineId(string $lineId): static
    {
        $this->lineId = $lineId;

        return $this;
    }

    public function getCustomerCreation(): ?CustomerCreation
    {
        return $this->customerCreation;
    }

    public function setCustomerCreation(?CustomerCreation $customerCreation): static
    {
        $this->customerCreation = $customerCreation;

        return $this;
    }

    public function hasCustomerCreation(): bool
    {
        return isset($this->customerCreation);
    }

    public function getBilledUntil(): \DateTimeInterface
    {
        return $this->billedUntil;
    }

    public function setBilledUntil(\DateTimeInterface $billedUntil): static
    {
        $this->billedUntil = $billedUntil;

        return $this;
    }

    public function getDetailsUrl(): ?string
    {
        return $this->detailsUrl;
    }

    public function setDetailsUrl(?string $detailsUrl): void
    {
        $this->detailsUrl = $detailsUrl;
    }
}
