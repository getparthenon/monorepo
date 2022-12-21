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

namespace Obol\Provider\Adyen\DataMapper;

use Obol\Model\Subscription;

class PaymentDetailsMapper
{
    use AddressTrait;

    public function mapSubscription(Subscription $subscription): array
    {
        // No Mandate because it needs an end date.

        return [
            'lineItems' => [
                'description' => null,
                'quantity' => null, // number of seats
            ],
            'billingAddress' => null,
            'amount' => [
                'currency' => 'usd',
                'value' => 1000, // Check if dollars or cents.
            ],
            'reference' => null,
            'paymentMethod' => [
                'type' => null,
                'number' => null,
                'expiryMonth' => null,
                'expiryYear' => null,
                'holderName' => null,
                'cvc' => null,
            ],
            'shopperReference' => null,
            'storePaymentMethod' => true,
            'shopperInteraction' => 'Ecommerce',
            'recurringProcessingModel' => 'UnscheduledCardOnFile',
            'returnUrl' => null, // Config
            'merchantAccount' => null, // config
        ];
    }
}
