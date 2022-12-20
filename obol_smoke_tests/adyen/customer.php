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
$config->setTestMode(true);
$config->setApiKey(getenv('ADYEN_API_KEY'));

$customerService = new \Obol\Provider\Adyen\CustomerService($config);

$customer = new \Obol\Models\Customer();
$customer->setType(\Obol\Models\Enum\CustomerType::INDIVIDUAL)
    ->setName('Smoke Test')
    ->setEmail('smoke.test@example.org')
    ->getAddress()->setCountryCode('US');

$customerCreationResult = $customerService->createCustomer($customer);

$fetchedCustomer = $customerService->fetchCustomer($customerCreationResult->getId());
