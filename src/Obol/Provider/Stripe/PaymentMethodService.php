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

namespace Obol\Provider\Stripe;

use Obol\Model\PaymentMethod\PaymentMethodCard;
use Obol\PaymentMethodServiceInterface;
use Obol\Provider\ProviderInterface;
use Stripe\StripeClient;

class PaymentMethodService implements PaymentMethodServiceInterface
{
    protected StripeClient $stripe;

    protected Config $config;

    /**
     * @param StripeClient $stripe
     */
    public function __construct(private ProviderInterface $provider, Config $config, ?StripeClient $stripe = null)
    {
        $this->config = $config;
        $this->stripe = $stripe ?? new StripeClient($this->config->getApiKey());
    }

    public function listCards(int $limit = 10, ?string $lastId = null): array
    {
        $payload = ['limit' => $limit];
        if (isset($lastId) && !empty($lastId)) {
            $payload['starting_after'] = $lastId;
        }
        $result = $this->stripe->paymentMethods->all($payload);
        $output = [];
        foreach ($result->data as $stripePayment) {
            $paymentMethodCard = new PaymentMethodCard();
            $paymentMethodCard->setId($stripePayment->id);
            $paymentMethodCard->setCustomerReference($stripePayment->customer);
            $paymentMethodCard->setLastFour($stripePayment->card->last4);
            $paymentMethodCard->setExpiryMonth($stripePayment->card->exp_month);
            $paymentMethodCard->setExpiryYear($stripePayment->card->exp_year);
            $paymentMethodCard->setBrand($stripePayment->card->brand);

            $createdAt = new \DateTime();
            $createdAt->setTimestamp($stripePayment->created);
            $paymentMethodCard->setCreatedAt($createdAt);
            $output[] = $paymentMethodCard;
        }

        return $output;
    }
}
