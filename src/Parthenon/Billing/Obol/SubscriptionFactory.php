<?php

namespace Parthenon\Billing\Obol;

use Obol\Model\BillingDetails;
use Obol\Model\Subscription;
use Parthenon\Billing\Plan\PlanPrice;

class SubscriptionFactory implements SubscriptionFactoryInterface
{
    public function createSubscription(
        BillingDetails $billingDetails,
        PlanPrice $planPrice,
        int $seatNumbers,
    ) : Subscription {
        $obolSubscription = new \Obol\Model\Subscription();
        $obolSubscription->setBillingDetails($billingDetails);
        $obolSubscription->setSeats($seatNumbers);
        $obolSubscription->setCostPerSeat($planPrice->getPriceAsMoney());
        if ($planPrice->hasPriceId()) {
            $obolSubscription->setPriceId($planPrice->getPriceId());
        }

        return $obolSubscription;
    }
}