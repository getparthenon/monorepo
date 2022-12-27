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
use Obol\Model\Charge;
use Obol\Model\PaymentDetails;
use Obol\Model\Subscription;
use Obol\Provider\Adyen\Config;

class PaymentDetailsMapper
{
    use AddressTrait;

    public function mapSubscription(Subscription $subscription, Config $config): array
    {
        // No Mandate because it needs an end date.

        if ($subscription->getBillingDetails()->usePrestoredCard()) {
            $paymentMethod = [
                'storedPaymentMethodId' => $subscription->getBillingDetails()->getPaymentReference(),
            ];
        } else {
            $paymentMethod = [
                'type' => 'scheme', // ??
                'holderName' => $subscription->getBillingDetails()->getCardDetails()->getName(),
            ];
            if ($config->isPciMode()) {
                $paymentMethod = array_merge($paymentMethod, [
                    'number' => $subscription->getBillingDetails()->getCardDetails()->getNumber(),
                    'expiryMonth' => $subscription->getBillingDetails()->getCardDetails()->getExpireDate(),
                    'expiryYear' => $subscription->getBillingDetails()->getCardDetails()->getExpireYear(),
                    'cvc' => $subscription->getBillingDetails()->getCardDetails()->getSecurityCode(),
                ]);
            } else {
                $paymentMethod = array_merge($paymentMethod, [
                    'encryptedCardNumber' => $subscription->getBillingDetails()->getCardDetails()->getNumber(),
                    'encryptedExpiryMonth' => $subscription->getBillingDetails()->getCardDetails()->getExpireDate(),
                    'encryptedExpiryYear' => $subscription->getBillingDetails()->getCardDetails()->getExpireYear(),
                    'encryptedSecurityCode' => $subscription->getBillingDetails()->getCardDetails()->getSecurityCode(),
                ]);
            }
        }

        return [
            'lineItems' => [[
                'description' => $subscription->getName(),
                'quantity' => $subscription->getSeats(), // number of seats
            ]],
            'billingAddress' => $this->mapAddress($subscription->getBillingDetails()->getAddress()),
            'amount' => [
                'currency' => $subscription->getCostPerSeat()->getCurrency()->getCurrencyCode(),
                'value' => $subscription->getTotalCost()->getMinorAmount()->toInt(),
            ],
            'reference' => $subscription->getBillingDetails()->getCustomerReference().' '.$subscription->getName(),
            'paymentMethod' => $paymentMethod,
            'shopperReference' => $subscription->getBillingDetails()->getCustomerReference(),
            'storePaymentMethod' => true,
            'shopperInteraction' => 'Ecommerce',
            'recurringProcessingModel' => 'UnscheduledCardOnFile',
            'returnUrl' => $config->getReturnUrl(), // Config
            'merchantAccount' => $config->getMerchantAccount(), // config
        ];
    }

    public function chargePayload(Charge $charge, Config $config): array
    {
        $billingDetails = $charge->getBillingDetails();

        $paymentMethod = [];
        if ($billingDetails->usePrestoredCard()) {
            throw new Exception('Has prestored card data for payload for generating card on file');
        }

        $paymentMethod = [
            'type' => 'scheme', // ??
            'holderName' => $charge->getBillingDetails()->getCardDetails()->getName(),
        ];
        if ($config->isPciMode()) {
            $paymentMethod = array_merge($paymentMethod, [
                'number' => $charge->getBillingDetails()->getCardDetails()->getNumber(),
                'expiryMonth' => $charge->getBillingDetails()->getCardDetails()->getExpireDate(),
                'expiryYear' => $charge->getBillingDetails()->getCardDetails()->getExpireYear(),
                'cvc' => $charge->getBillingDetails()->getCardDetails()->getSecurityCode(),
            ]);
        } else {
            $paymentMethod = array_merge($paymentMethod, [
                'encryptedCardNumber' => $charge->getBillingDetails()->getCardDetails()->getNumber(),
                'encryptedExpiryMonth' => $charge->getBillingDetails()->getCardDetails()->getExpireDate(),
                'encryptedExpiryYear' => $charge->getBillingDetails()->getCardDetails()->getExpireYear(),
                'encryptedSecurityCode' => $charge->getBillingDetails()->getCardDetails()->getSecurityCode(),
            ]);
        }

        return [
            'billingAddress' => $this->mapAddress($billingDetails->getAddress()),
            'amount' => [
                'currency' => $charge->getAmount()->getCurrency()->getCurrencyCode(),
                'value' => $charge->getAmount()->getMinorAmount()->toInt(),
            ],
            'reference' => $billingDetails->getCustomerReference(),
            'paymentMethod' => $paymentMethod,
            'shopperReference' => $billingDetails->getCustomerReference(),
            'storePaymentMethod' => true,
            'shopperInteraction' => 'Ecommerce',
            'recurringProcessingModel' => 'UnscheduledCardOnFile',
            'returnUrl' => $config->getReturnUrl(), // Config
            'merchantAccount' => $config->getMerchantAccount(), // config
        ];
    }

    public function buildPaymentDetails(array $response): PaymentDetails
    {
        $paymentDetails = new PaymentDetails();
        $paymentDetails->setCustomerReference($response['additionalData']['recurring.recurringDetailReference'])
            ->setPaymentReference($response['additionalData']['recurring.shopperReference']);

        return $paymentDetails;
    }
}
