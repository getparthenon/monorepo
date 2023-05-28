<?php

declare(strict_types=1);

/*
 * Copyright Iain Cambridge 2020-2023.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: TBD ( 3 years after 2.2.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
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
