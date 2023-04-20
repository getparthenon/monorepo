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

namespace Obol\Model\Events;

abstract class AbstractCharge
{
    private string $externalPaymentId;

    private int $amount;

    private string $currency;

    private string $externalCustomerId;

    private string $externalPaymentMethodId;

    private string $externalInvoiceId;

    public function getExternalPaymentId(): string
    {
        return $this->externalPaymentId;
    }

    public function setExternalPaymentId(string $externalPaymentId): void
    {
        $this->externalPaymentId = $externalPaymentId;
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

    public function getExternalCustomerId(): string
    {
        return $this->externalCustomerId;
    }

    public function setExternalCustomerId(string $externalCustomerId): void
    {
        $this->externalCustomerId = $externalCustomerId;
    }

    public function getExternalPaymentMethodId(): string
    {
        return $this->externalPaymentMethodId;
    }

    public function setExternalPaymentMethodId(string $externalPaymentMethodId): void
    {
        $this->externalPaymentMethodId = $externalPaymentMethodId;
    }

    public function getExternalInvoiceId(): string
    {
        return $this->externalInvoiceId;
    }

    public function setExternalInvoiceId(string $externalInvoiceId): void
    {
        $this->externalInvoiceId = $externalInvoiceId;
    }
}
