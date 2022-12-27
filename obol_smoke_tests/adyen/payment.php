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

$config = new \Obol\Provider\Adyen\Config();
$config->setTestMode(true)
    ->setMerchantAccount(getenv('ADYEN_MERCHANT'))
    ->setPciMode(true)
    ->setApiKey(getenv('ADYEN_API_KEY'))
    ->setReturnUrl('http://localhost');

$paymentSystem = new \Obol\Provider\Adyen\PaymentService($config);

$subscription = new \Obol\Model\Subscription();

$address = new \Obol\Model\Address();
$address->setCountryCode('US');

$billingDetails = new \Obol\Model\BillingDetails();
$billingDetails->setName('Test User')
    ->setEmail('test.user@example.org')
    ->setAddress($address);

$cardDetails = new \Obol\Model\CardDetails();
$cardDetails->setName('Test User')
    ->setNumber('4111111111111111')
    ->setExpireDate('03')
    ->setExpireYear('2030')
    ->setSecurityCode('737');

$billingDetails->setCardDetails($cardDetails);

$subscription->setName('Test')
    ->setSeats(10)
    ->setBillingDetails($billingDetails)
    ->setCostPerSeat(Brick\Money\Money::of(10, 'USD'));

$creation = $paymentSystem->startSubscription($subscription);

var_dump($creation);

$cardOnFile = $paymentSystem->createCardOnFile($billingDetails);

var_dump($cardOnFile);
