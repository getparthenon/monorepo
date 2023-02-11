<?php

namespace Parthenon\Billing\Obol;

use Mpdf\Tag\P;
use Obol\Model\SubscriptionCreationResponse;
use Parthenon\Billing\Entity\CustomerInterface;
use Parthenon\Billing\Entity\Payment;

class PaymentFactory implements PaymentFactoryInterface
{
    public function fromSubscriptionConfirm(CustomerInterface $customer, SubscriptionCreationResponse $subscriptionCreationResponse): Payment
    {

        $payment = new Payment();
        $payment->setPaymentReference($subscriptionCreationResponse->getPaymentDetails()->getPaymentReference());
        $payment->setMoneyAmount($subscriptionCreationResponse->getPaymentDetails()->getAmount());
        $payment->setCustomer($customer);
        $payment->setCompleted(true);
        $payment->setCreatedAt(new \DateTime('now'));
        $payment->setUpdatedAt(new \DateTime('now'));

        return $payment;
    }
}