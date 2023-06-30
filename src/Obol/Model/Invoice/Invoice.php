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

namespace Obol\Model\Invoice;

class Invoice
{
    private string $id;

    private string $customerReference;

    private array $lines = [];

    private int $amountDue;

    private int $amountPaid;

    private int $total;

    private string $currency;

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

    /**
     * @return InvoiceLine[]
     */
    public function getLines(): array
    {
        return $this->lines;
    }

    public function setLines(array $lines): void
    {
        $this->lines = $lines;
    }

    public function getAmountDue(): int
    {
        return $this->amountDue;
    }

    public function setAmountDue(int $amountDue): void
    {
        $this->amountDue = $amountDue;
    }

    public function getAmountPaid(): int
    {
        return $this->amountPaid;
    }

    public function setAmountPaid(int $amountPaid): void
    {
        $this->amountPaid = $amountPaid;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): void
    {
        $this->total = $total;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    public function addInvoiceLine(InvoiceLine $invoiceLine): void
    {
        $this->lines[] = $invoiceLine;
    }
}
