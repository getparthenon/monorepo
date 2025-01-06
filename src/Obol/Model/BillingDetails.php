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

class BillingDetails
{
    protected string $name = '';

    protected string $email = '';

    protected ?string $taxNumber = null;

    protected Address $address;

    protected ?CardDetails $cardDetails;

    protected ?string $customerReference;

    protected string $storedPaymentReference;

    public function __construct()
    {
        $this->address = new Address();
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTaxNumber(): ?string
    {
        return $this->taxNumber;
    }

    public function setTaxNumber(?string $taxNumber): static
    {
        $this->taxNumber = $taxNumber;

        return $this;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getCardDetails(): CardDetails
    {
        if (!isset($this->cardDetails)) {
            // TODO make custom exception
            throw new \Exception('No card details set.');
        }

        return $this->cardDetails;
    }

    public function setCardDetails(?CardDetails $cardDetails): void
    {
        $this->cardDetails = $cardDetails;
    }

    public function hasStoredPaymentReference(): bool
    {
        return isset($this->storedPaymentReference);
    }

    public function getStoredPaymentReference(): string
    {
        return $this->storedPaymentReference;
    }

    public function setStoredPaymentReference(string $storedPaymentReference): void
    {
        $this->storedPaymentReference = $storedPaymentReference;
    }

    public function getCustomerReference(): string
    {
        if (!isset($this->customerReference)) {
            throw new \Exception('No customer reference set');
        }

        return $this->customerReference;
    }

    public function setCustomerReference(?string $customerReference): static
    {
        $this->customerReference = $customerReference;

        return $this;
    }

    public function hasCustomerReference(): bool
    {
        return isset($this->customerReference);
    }

    public function usePrestoredCard(): bool
    {
        return isset($this->storedPaymentReference);
    }
}
