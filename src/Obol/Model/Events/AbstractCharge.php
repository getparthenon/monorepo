<?php

declare(strict_types=1);

/*
 * Copyright Humbly Arrogant Software Limited 2020-2024
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: 26.06.2026 ( 3 years after 2.2.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace Obol\Model\Events;

abstract class AbstractCharge
{
    private string $id;

    private string $externalPaymentId;

    private int $amount;

    private string $currency;

    private ?string $externalCustomerId = null;

    private ?string $externalPaymentMethodId = null;

    private string $detailsLink;

    private ?string $externalInvoiceId = null;

    private \DateTimeInterface $createdAt;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getPaymentReference(): string
    {
        return $this->externalPaymentId;
    }

    public function setExternalPaymentId(string $externalPaymentId): void
    {
        $this->externalPaymentId = $externalPaymentId;
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

    public function hasExternalCustomerId(): bool
    {
        return isset($this->externalCustomerId);
    }

    public function getExternalCustomerId(): ?string
    {
        return $this->externalCustomerId;
    }

    public function setExternalCustomerId(?string $externalCustomerId): void
    {
        $this->externalCustomerId = $externalCustomerId;
    }

    public function hasExternalPaymentMethodId(): bool
    {
        return isset($this->externalPaymentMethodId);
    }

    public function getExternalPaymentMethodId(): ?string
    {
        return $this->externalPaymentMethodId;
    }

    public function setExternalPaymentMethodId(string $externalPaymentMethodId): void
    {
        $this->externalPaymentMethodId = $externalPaymentMethodId;
    }

    public function hasExternalInvoiceId(): bool
    {
        return isset($this->externalInvoiceId);
    }

    public function getExternalInvoiceId(): ?string
    {
        return $this->externalInvoiceId;
    }

    public function setExternalInvoiceId(?string $externalInvoiceId): void
    {
        $this->externalInvoiceId = $externalInvoiceId;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getDetailsLink(): string
    {
        return $this->detailsLink;
    }

    public function setDetailsLink(string $detailsLink): void
    {
        $this->detailsLink = $detailsLink;
    }
}
