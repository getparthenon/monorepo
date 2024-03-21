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

namespace Obol\Model;

use Brick\Money\Money;

class CreatePrice
{
    private Money $money;

    private ?string $productReference = null;

    private bool $recurring;

    private ?string $paymentSchedule;

    private bool $includingTax = false;

    public function getMoney(): Money
    {
        return $this->money;
    }

    public function setMoney(Money $money): void
    {
        $this->money = $money;
    }

    public function getProductReference(): ?string
    {
        return $this->productReference;
    }

    public function setProductReference(?string $productReference): void
    {
        $this->productReference = $productReference;
    }

    public function isRecurring(): bool
    {
        return $this->recurring;
    }

    public function setRecurring(bool $recurring): void
    {
        $this->recurring = $recurring;
    }

    public function getPaymentSchedule(): ?string
    {
        return $this->paymentSchedule;
    }

    public function setPaymentSchedule(?string $paymentSchedule): void
    {
        $this->paymentSchedule = $paymentSchedule;
    }

    public function isIncludingTax(): bool
    {
        return $this->includingTax;
    }

    public function setIncludingTax(bool $includingTax): void
    {
        $this->includingTax = $includingTax;
    }
}
