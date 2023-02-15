<?php

namespace Parthenon\Billing\Obol;

use Obol\Model\BillingDetails;
use Parthenon\Billing\Entity\CustomerInterface;
use Parthenon\Billing\Entity\PaymentDetails;

interface BillingDetailsFactoryInterface
{
    public function createFromCustomerAndPaymentDetails(CustomerInterface $customer, PaymentDetails $paymentDetails): BillingDetails;
}