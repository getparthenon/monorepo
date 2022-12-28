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

require_once '../../vendor/autoload.php';

$config = new \Obol\Provider\Stripe\Config();
$config->setPciMode(true)
    ->setApiKey(getenv('STRIPE_API_KEY'));

$paymentSystem = new \Obol\Provider\Stripe\PaymentService($config);

$subscription = new \Obol\Model\Subscription();

$address = new \Obol\Model\Address();
$address->setCountryCode('US');

$billingDetails = new \Obol\Model\BillingDetails();
$billingDetails->setName('Test User')
    ->setEmail('test.user@example.org')
    ->setAddress($address);

$cardDetails = new \Obol\Model\CardDetails();
$cardDetails->setName('Test User')
    ->setNumber('4242 4242 4242 4242')
    ->setExpireDate('03')
    ->setExpireYear('2030')
    ->setSecurityCode('737');

$billingDetails->setCardDetails($cardDetails);

$subscription->setName('Test')
    ->setSeats(10)
    ->setBillingDetails($billingDetails)
    ->setCostPerSeat(Brick\Money\Money::of(10, 'USD'))
    ->setPriceId('price_1L46c5IfxpuZtqIzs5lM6dGE');

$creation = $paymentSystem->startSubscription($subscription);

var_dump($creation);
$cardOnFile = $paymentSystem->createCardOnFile($billingDetails);
$billingDetails->setPaymentReference($cardOnFile->getPaymentDetails()->getPaymentReference()); // TODO change to paymentDetailsReference
var_dump($cardOnFile);

$charge = new \Obol\Model\Charge();
$charge->setName('Smoke Test');
$charge->setBillingDetails($billingDetails);
$charge->setAmount(\Brick\Money\Money::of('19.99', 'USD'));

$cardOnFileCharged = $paymentSystem->chargeCardOnFile($charge);

var_dump($cardOnFileCharged);
