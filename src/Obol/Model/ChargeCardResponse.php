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

class ChargeCardResponse
{
    protected ?PaymentDetails $paymentDetails = null;

    protected ?ChargeFailure $chargeFailure = null;

    protected bool $successful;

    public function getChargeFailure(): ?ChargeFailure
    {
        return $this->chargeFailure;
    }

    public function setChargeFailure(?ChargeFailure $chargeFailure): void
    {
        $this->chargeFailure = $chargeFailure;
    }

    public function isSuccessful(): bool
    {
        return $this->successful;
    }

    public function setSuccessful(bool $successful): void
    {
        $this->successful = $successful;
    }

    public function getPaymentDetails(): ?PaymentDetails
    {
        return $this->paymentDetails;
    }

    public function setPaymentDetails(?PaymentDetails $paymentDetails): void
    {
        $this->paymentDetails = $paymentDetails;
    }
}
