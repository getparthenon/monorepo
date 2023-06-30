<?php

declare(strict_types=1);

/*
 * Copyright Humbly Arrogant Software Limited 2020-2023.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: 26.06.2026 ( 3 years after 2.2.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
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
