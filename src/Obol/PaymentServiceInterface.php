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

namespace Obol;

use Obol\Model\BillingDetails;
use Obol\Model\CardOnFileResponse;
use Obol\Model\Charge;
use Obol\Model\ChargeCardResponse;
use Obol\Model\Subscription;
use Obol\Model\SubscriptionCreationResponse;

interface PaymentServiceInterface
{
    public function startSubscription(Subscription $subscription): SubscriptionCreationResponse;

    public function stopSubscription(Subscription $subscription): void;

    public function createCardOnFile(BillingDetails $billingDetails): CardOnFileResponse;

    public function deleteCardFile(BillingDetails $cardFile): void;

    public function chargeCardOnFile(Charge $cardFile): ChargeCardResponse;
}
