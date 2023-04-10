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

namespace Obol\Model\Subscription;

class UpdatePaymentMethod
{
    private string $paymentMethodReference;

    private string $subscriptionId;

    public function getPaymentMethodReference(): string
    {
        return $this->paymentMethodReference;
    }

    public function setPaymentMethodReference(string $paymentMethodReference): void
    {
        $this->paymentMethodReference = $paymentMethodReference;
    }

    public function getSubscriptionId(): string
    {
        return $this->subscriptionId;
    }

    public function setSubscriptionId(string $subscriptionId): void
    {
        $this->subscriptionId = $subscriptionId;
    }
}
