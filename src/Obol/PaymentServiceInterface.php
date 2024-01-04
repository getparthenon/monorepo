<?php

declare(strict_types=1);

/*
 * Copyright Humbly Arrogant Software Limited 2020-2024
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: 26.06.2026 ( 3 years after 2.2.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace Obol;

use Obol\Exception\ProviderFailureException;
use Obol\Model\BillingDetails;
use Obol\Model\CancelSubscription;
use Obol\Model\CardOnFileResponse;
use Obol\Model\Charge;
use Obol\Model\ChargeCardResponse;
use Obol\Model\FrontendCardProcess;
use Obol\Model\Subscription;
use Obol\Model\SubscriptionCancellation;
use Obol\Model\SubscriptionCreationResponse;

interface PaymentServiceInterface
{
    /**
     * @throws ProviderFailureException
     */
    public function startSubscription(Subscription $subscription): SubscriptionCreationResponse;

    /**
     * @throws ProviderFailureException
     */
    public function stopSubscription(CancelSubscription $cancelSubscription): SubscriptionCancellation;

    /**
     * @throws ProviderFailureException
     */
    public function createCardOnFile(BillingDetails $billingDetails): CardOnFileResponse;

    /**
     * @throws ProviderFailureException
     */
    public function deleteCardFile(BillingDetails $cardFile): void;

    /**
     * @throws ProviderFailureException
     */
    public function makeCardDefault(BillingDetails $billingDetails): void;

    /**
     * @throws ProviderFailureException
     */
    public function chargeCardOnFile(Charge $cardFile): ChargeCardResponse;

    /**
     * @throws ProviderFailureException
     */
    public function startFrontendCreateCardOnFile(BillingDetails $billingDetails): FrontendCardProcess;

    public function list(int $limit = 10, ?string $lastId = null): array;
}
