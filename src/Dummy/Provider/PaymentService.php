<?php

declare(strict_types=1);

/*
 * Copyright (C) 2020-2024 Iain Cambridge
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU LESSER GENERAL PUBLIC LICENSE as published by
 * the Free Software Foundation, either version 2.1 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace App\Dummy\Provider;

use Obol\Model\BillingDetails;
use Obol\Model\CancelSubscription;
use Obol\Model\CardFile;
use Obol\Model\CardOnFileResponse;
use Obol\Model\Charge;
use Obol\Model\ChargeCardResponse;
use Obol\Model\FrontendCardProcess;
use Obol\Model\PaymentDetails;
use Obol\Model\Subscription;
use Obol\Model\SubscriptionCancellation;
use Obol\Model\SubscriptionCreationResponse;
use Obol\PaymentServiceInterface;

class PaymentService implements PaymentServiceInterface
{
    public function startSubscription(Subscription $subscription): SubscriptionCreationResponse
    {
        $paymentDetails = new PaymentDetails();
        $paymentDetails->setAmount($subscription->getTotalCost());
        $paymentDetails->setCustomerReference($subscription->getBillingDetails()->getCustomerReference());
        $paymentDetails->setStoredPaymentReference($subscription->getBillingDetails()->getStoredPaymentReference());
        $paymentDetails->setPaymentReference(bin2hex(random_bytes(32)));

        $subscriptionCreation = new SubscriptionCreationResponse();
        $subscriptionCreation->setSubscriptionId(bin2hex(random_bytes(32)));
        $subscriptionCreation->setBilledUntil(new \DateTime('+1 month'));
        $subscriptionCreation->setPaymentDetails($paymentDetails);
        $subscriptionCreation->setLineId(bin2hex(random_bytes(32)));

        return $subscriptionCreation;
    }

    public function stopSubscription(CancelSubscription $cancelSubscription): SubscriptionCancellation
    {
        $subscriptionCancellation = new SubscriptionCancellation();
        $subscriptionCancellation->setSubscription($cancelSubscription->getSubscription());

        return $subscriptionCancellation;
    }

    public function createCardOnFile(BillingDetails $billingDetails): CardOnFileResponse
    {
        $cardFile = new CardFile();
        $cardFile->setCustomerReference($billingDetails->getCustomerReference());
        $cardFile->setStoredPaymentReference(bin2hex(random_bytes(32)));
        $cardFile->setBrand('test');
        $cardFile->setLastFour('4242');
        $cardFile->setExpiryMonth('03');
        $cardFile->setExpiryYear('32');

        $cardOnFile = new CardOnFileResponse();
        $cardOnFile->setCardFile($cardFile);

        return $cardOnFile;
    }

    public function deleteCardFile(BillingDetails $cardFile): void
    {
    }

    public function chargeCardOnFile(Charge $cardFile): ChargeCardResponse
    {
        $paymentDetails = new PaymentDetails();
        $paymentDetails->setAmount($subscription->getTotalCost());
        $paymentDetails->setCustomerReference($subscription->getBillingDetails()->getCustomerReference());
        $paymentDetails->setStoredPaymentReference($subscription->getBillingDetails()->getStoredPaymentReference());
        $paymentDetails->setPaymentReference(bin2hex(random_bytes(32)));

        $chargeCardResponse = new ChargeCardResponse();
        $chargeCardResponse->setPaymentDetails($paymentDetails);

        return $chargeCardResponse;
    }

    public function startFrontendCreateCardOnFile(BillingDetails $billingDetails): FrontendCardProcess
    {
        $frontendCardProcess = new FrontendCardProcess();
        $frontendCardProcess->setCustomerReference($billingDetails->getCustomerReference());
        $frontendCardProcess->setToken(bin2hex(random_bytes(32)));

        return $frontendCardProcess;
    }

    public function makeCardDefault(BillingDetails $billingDetails): void
    {
        // TODO: Implement makeCardDefault() method.
    }

    public function list(int $limit = 10, ?string $lastId = null): array
    {
        // TODO: Implement list() method.
    }
}
