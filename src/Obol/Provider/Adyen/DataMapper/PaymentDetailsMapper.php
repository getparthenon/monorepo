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

use _PHPStan_b8e553790\Nette\Neon\Exception;
use Obol\Model\BillingDetails;
use Obol\Model\Subscription;

class PaymentDetailsMapper
{
    use AddressTrait;

    public function mapSubscription(Subscription $subscription): array
    {
        // No Mandate because it needs an end date.

        $paymentMethod = [];

        if ($subscription->getBillingDetails()->usePrestoredCard()) {
            $paymentMethod = [
                'storedPaymentMethodId' => $subscription->getBillingDetails()->getPaymentReference(),
            ];
        } else {
            $paymentMethod = [
                'type' => 'scheme', // ??
                'number' => $subscription->getBillingDetails()->getCardDetails()->getNumber(),
                'expiryMonth' => $subscription->getBillingDetails()->getCardDetails()->getExpireDate(),
                'expiryYear' => $subscription->getBillingDetails()->getCardDetails()->getExpireYear(),
                'holderName' => $subscription->getBillingDetails()->getCardDetails()->getName(),
                'cvc' => $subscription->getBillingDetails()->getCardDetails()->getSecurityCode(),
            ];
        }

        return [
            'lineItems' => [
                'description' => $subscription->getName(),
                'quantity' => $subscription->getSeats(), // number of seats
            ],
            'billingAddress' => $this->mapAddress($subscription->getBillingDetails()->getAddress()),
            'amount' => [
                'currency' => 'usd',
                'value' => 1000, // Check if dollars or cents.
            ],
            'reference' => $subscription->getBillingDetails()->getCustomerReference().' '.$subscription->getName(),
            'paymentMethod' => $paymentMethod,
            'shopperReference' => $subscription->getBillingDetails()->getCustomerReference(),
            'storePaymentMethod' => true,
            'shopperInteraction' => 'Ecommerce',
            'recurringProcessingModel' => 'UnscheduledCardOnFile',
            'returnUrl' => null, // Config
            'merchantAccount' => null, // config
        ];
    }

    public function mapBillingDetails(BillingDetails $billingDetails): array
    {
        // No Mandate because it needs an end date.

        $paymentMethod = [];
        if ($billingDetails->usePrestoredCard()) {
            throw new Exception('Has prestored card data for payload for generating card on file');
        }

        $paymentMethod = [
            'type' => 'scheme', // ??
            'number' => $billingDetails->getCardDetails()->getNumber(),
            'expiryMonth' => $billingDetails->getCardDetails()->getExpireDate(),
            'expiryYear' => $billingDetails->getCardDetails()->getExpireYear(),
            'holderName' => $billingDetails->getCardDetails()->getName(),
            'cvc' => $billingDetails->getCardDetails()->getSecurityCode(),
        ];

        return [
            'billingAddress' => $this->mapAddress($billingDetails->getAddress()),
            'amount' => [
                'currency' => 'usd',
                'value' => 0, // Check if dollars or cents.
            ],
            'reference' => $billingDetails->getCustomerReference(),
            'paymentMethod' => $paymentMethod,
            'shopperReference' => $billingDetails->getCustomerReference(),
            'storePaymentMethod' => true,
            'shopperInteraction' => 'Ecommerce',
            'recurringProcessingModel' => 'UnscheduledCardOnFile',
            'returnUrl' => null, // Config
            'merchantAccount' => null, // config
        ];
    }
}
