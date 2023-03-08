<?php

namespace Parthenon\Billing\Obol;

use Obol\Model\BillingDetails;
use Obol\Model\Subscription;
use Parthenon\Billing\Plan\PlanPrice;

interface SubscriptionFactoryInterface
{
    public function createSubscription(BillingDetails $billingDetails, PlanPrice $planPrice, int $seatNumbers,): Subscription;
}