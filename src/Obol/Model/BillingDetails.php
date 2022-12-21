<?php

declare(strict_types=1);

/*
 * Copyright Iain Cambridge 2020-2022.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: TBD ( 3 years after 2.2.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace Obol\Model;

class BillingDetails
{
    protected string $name;

    protected string $email;

    protected ?string $taxNumber = null;

    protected Address $address;

    protected ?CardDetails $cardDetails;

    protected string $customerReference;

    protected string $paymentReference;

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

    public function getPaymentReference(): string
    {
        return $this->paymentReference;
    }

    public function setPaymentReference(string $paymentReference): void
    {
        $this->paymentReference = $paymentReference;
    }

    public function getCustomerReference(): string
    {
        if (!isset($this->customerReference)) {
            throw new \Exception('No customer reference set');
        }

        return $this->customerReference;
    }

    public function setCustomerReference(string $customerReference): static
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
        return isset($this->paymentReference);
    }
}
