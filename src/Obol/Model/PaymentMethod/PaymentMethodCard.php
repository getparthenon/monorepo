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
