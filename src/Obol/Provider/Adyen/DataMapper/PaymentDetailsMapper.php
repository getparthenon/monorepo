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
            $paymentMethod = $this->getPaymentMethod($subscription->getBillingDetails(), $config);
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

    public function chargeCardPayload(Charge $charge, Config $config): array
    {
        // No Mandate because it needs an end date.

        if ($charge->getBillingDetails()->usePrestoredCard()) {
            $paymentMethod = [
                'storedPaymentMethodId' => $charge->getBillingDetails()->getPaymentReference(),
            ];
        } else {
            $paymentMethod = $this->getPaymentMethod($charge->getBillingDetails(), $config);
        }

        return [
            'billingAddress' => $this->mapAddress($charge->getBillingDetails()->getAddress()),
            'amount' => [
                'currency' => $charge->getAmount()->getCurrency()->getCurrencyCode(),
                'value' => $charge->getAmount()->getMinorAmount()->toInt(),
            ],
            'reference' => $charge->getBillingDetails()->getCustomerReference().' '.$charge->getName(),
            'paymentMethod' => $paymentMethod,
            'shopperReference' => $charge->getBillingDetails()->getCustomerReference(),
            'shopperInteraction' => 'ContAuth',
            'recurringProcessingModel' => 'UnscheduledCardOnFile',
            'returnUrl' => $config->getReturnUrl(), // Config
            'merchantAccount' => $config->getMerchantAccount(), // config
        ];
    }

    public function addCardToFilePayload(BillingDetails $billingDetails, Config $config): array
    {
        if ($billingDetails->usePrestoredCard()) {
            throw new Exception('Has prestored card data for payload for generating card on file');
        }

        $paymentMethod = $this->getPaymentMethod($billingDetails, $config);

        return [
            'billingAddress' => $this->mapAddress($billingDetails->getAddress()),
            'amount' => [
                'currency' => 'USD',
                'value' => 0,
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
        $paymentDetails->setCustomerReference($response['additionalData']['recurring.shopperReference'])
            ->setPaymentReference($response['additionalData']['recurring.recurringDetailReference']);

        return $paymentDetails;
    }

    /**
     * @return array|string[]
     *
     * @throws \Exception
     */
    private function getPaymentMethod(BillingDetails $billingDetails, Config $config): array
    {
        $paymentMethod = [
            'type' => 'scheme', // ??
            'holderName' => $billingDetails->getCardDetails()->getName(),
        ];
        if ($config->isPciMode()) {
            $paymentMethod = array_merge($paymentMethod, [
                'number' => $billingDetails->getCardDetails()->getNumber(),
                'expiryMonth' => $billingDetails->getCardDetails()->getExpireDate(),
                'expiryYear' => $billingDetails->getCardDetails()->getExpireYear(),
                'cvc' => $billingDetails->getCardDetails()->getSecurityCode(),
            ]);
        } else {
            $paymentMethod = array_merge($paymentMethod, [
                'encryptedCardNumber' => $billingDetails->getCardDetails()->getNumber(),
                'encryptedExpiryMonth' => $billingDetails->getCardDetails()->getExpireDate(),
                'encryptedExpiryYear' => $billingDetails->getCardDetails()->getExpireYear(),
                'encryptedSecurityCode' => $billingDetails->getCardDetails()->getSecurityCode(),
            ]);
        }

        return $paymentMethod;
    }
}
