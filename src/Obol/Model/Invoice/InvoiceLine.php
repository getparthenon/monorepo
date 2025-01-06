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

namespace Obol\Model\Invoice;

class InvoiceLine
{
    private string $id;

    private int $amount;

    private string $currency;

    private ?string $mainSubscriptionReference = null;

    private ?string $childSubscriptionReference = null;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
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

    public function getMainSubscriptionReference(): ?string
    {
        return $this->mainSubscriptionReference;
    }

    public function setMainSubscriptionReference(?string $mainSubscriptionReference): void
    {
        $this->mainSubscriptionReference = $mainSubscriptionReference;
    }

    public function getChildSubscriptionReference(): ?string
    {
        return $this->childSubscriptionReference;
    }

    public function setChildSubscriptionReference(?string $childSubscriptionReference): void
    {
        $this->childSubscriptionReference = $childSubscriptionReference;
    }

    public function hasReferences(): bool
    {
        return isset($this->mainSubscriptionReference) && isset($this->childSubscriptionReference);
    }
}
