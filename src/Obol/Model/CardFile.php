<?php

declare(strict_types=1);

/*
 * Copyright (C) 2020-2025 Iain Cambridge
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

class CardFile
{
    protected string $expiryMonth;

    protected string $expiryYear;

    protected string $lastFour;

    protected string $brand;

    protected string $storedPaymentReference;

    protected string $customerReference;

    public function getExpiryMonth(): string
    {
        return $this->expiryMonth;
    }

    public function setExpiryMonth(string $expiryMonth): static
    {
        $this->expiryMonth = $expiryMonth;

        return $this;
    }

    public function getExpiryYear(): string
    {
        return $this->expiryYear;
    }

    public function setExpiryYear(string $expiryYear): static
    {
        $this->expiryYear = $expiryYear;

        return $this;
    }

    public function getLastFour(): string
    {
        return $this->lastFour;
    }

    public function setLastFour(string $lastFour): static
    {
        $this->lastFour = $lastFour;

        return $this;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getStoredPaymentReference(): string
    {
        return $this->storedPaymentReference;
    }

    public function setStoredPaymentReference(string $storedPaymentReference): static
    {
        $this->storedPaymentReference = $storedPaymentReference;

        return $this;
    }

    public function getCustomerReference(): string
    {
        return $this->customerReference;
    }

    public function setCustomerReference(string $customerReference): static
    {
        $this->customerReference = $customerReference;

        return $this;
    }
}
