<?php

declare(strict_types=1);

/*
 * Copyright Humbly Arrogant Software Limited 2020-2023.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: 26.06.2026 ( 3 years after 2.2.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace App\Billing;

use App\Entity\Price;
use App\Entity\Product;
use App\Entity\SubscriptionPlan;
use Parthenon\Billing\Entity\ChargeBack;
use Parthenon\Billing\Entity\Payment;
use Parthenon\Billing\Entity\PriceInterface;
use Parthenon\Billing\Entity\ProductInterface;
use Parthenon\Billing\Entity\Subscription;
use Parthenon\Billing\Entity\SubscriptionPlanInterface;
use Parthenon\Billing\Factory\EntityFactoryInterface;

class EntityFactory implements EntityFactoryInterface
{
    public function getProductEntity(): ProductInterface
    {
        return new Product();
    }

    public function getPriceEntity(): PriceInterface
    {
        return new Price();
    }

    public function getSubscriptionPlanEntity(): SubscriptionPlanInterface
    {
        return new SubscriptionPlan();
    }

    public function getSubscriptionEntity(): Subscription
    {
        return new \App\Entity\Subscription();
    }

    public function getPaymentEntity(): Payment
    {
        return new \App\Entity\Payment();
    }

    public function getChargeBackEntity(): ChargeBack
    {
        return new ChargeBack();
    }
}