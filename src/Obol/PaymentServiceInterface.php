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

use Obol\Model\CardDetails;
use Obol\Model\CardFile;
use Obol\Model\CardFileDeletedResponse;
use Obol\Model\CardOnFileResponse;
use Obol\Model\ChargeCardResponse;
use Obol\Model\Subscription;
use Obol\Model\SubscriptionCreationResponse;
use Obol\Model\SubscriptionStoppedResponse;

interface PaymentServiceInterface
{
    public function startSubscription(Subscription $subscription): SubscriptionCreationResponse;

    public function stopSubscription(): SubscriptionStoppedResponse;

    public function createCardOnFile(CardDetails $cardDetails): CardOnFileResponse;

    public function deleteCardFile(CardFile $cardFile): CardFileDeletedResponse;

    public function chargeCardOnFile(CardFile $cardFile): ChargeCardResponse;
}
