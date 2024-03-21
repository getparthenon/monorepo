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

    public function subscriptionPayload(Subscription $subscription, Config $config): array
    {
        if ($subscription->getBillingDetails()->usePrestoredCard()) {
            $paymentMethod = [
                'storedPaymentMethodId' => $subscription->getBillingDetails()->getStoredPaymentReference(),
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

    public function subscriptionCheckoutPayload(Subscription $subscription, Config $config): array
    {
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
                'storedPaymentMethodId' => $charge->getBillingDetails()->getStoredPaymentReference(),
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

    public function deletePaymentPayload(BillingDetails $billingDetails, Config $config): array
    {
        return [
            'shopperReference' => $billingDetails->getCustomerReference(),
            'recuringDetailReference' => $billingDetails->getStoredPaymentReference(),
            'merchantAccount' => $config->getMerchantAccount(),
        ];
    }

    public function buildPaymentDetails(array $response): PaymentDetails
    {
        $paymentDetails = new PaymentDetails();
        $paymentDetails->setCustomerReference($response['additionalData']['recurring.shopperReference'])
            ->setStoredPaymentReference($response['additionalData']['recurring.recurringDetailReference']);

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
