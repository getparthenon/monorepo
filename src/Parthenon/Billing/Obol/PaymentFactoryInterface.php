<?php

namespace Parthenon\Billing\Obol;

use Obol\Model\SubscriptionCreationResponse;
use Parthenon\Billing\Entity\CustomerInterface;
use Parthenon\Billing\Entity\Payment;

interface PaymentFactoryInterface
{
    public function fromSubscriptionConfirm(CustomerInterface $customer, SubscriptionCreationResponse $subscriptionCreationResponse): Payment;
}