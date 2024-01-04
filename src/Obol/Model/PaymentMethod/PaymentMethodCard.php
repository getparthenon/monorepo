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

namespace Obol\Model\PaymentMethod;

class PaymentMethodCard
{
    protected string $id;

    protected string $customerReference;

    protected ?string $name = null;

    protected bool $default;

    protected string $lastFour;

    protected string $expiryMonth;

    protected string $expiryYear;

    protected \DateTimeInterface $createdAt;

    private string $brand;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getCustomerReference(): string
    {
        return $this->customerReference;
    }

    public function setCustomerReference(string $customerReference): void
    {
        $this->customerReference = $customerReference;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function isDefault(): bool
    {
        return $this->default;
    }

    public function setDefault(bool $default): void
    {
        $this->default = $default;
    }

    public function getLastFour(): string
    {
        return $this->lastFour;
    }

    public function setLastFour(string $lastFour): void
    {
        $this->lastFour = $lastFour;
    }

    public function getExpiryMonth(): string
    {
        return $this->expiryMonth;
    }

    public function setExpiryMonth(string $expiryMonth): void
    {
        $this->expiryMonth = $expiryMonth;
    }

    public function getExpiryYear(): string
    {
        return $this->expiryYear;
    }

    public function setExpiryYear(string $expiryYear): void
    {
        $this->expiryYear = $expiryYear;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): void
    {
        $this->brand = $brand;
    }
}
