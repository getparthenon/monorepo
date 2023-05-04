<?php

declare(strict_types=1);

/*
 * Copyright Iain Cambridge 2020-2023.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: TBD ( 3 years after 2.2.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace Obol\Provider\Stripe;

use Brick\Money\Money;
use Obol\Model\Subscription;
use Obol\Model\Subscription\UpdatePaymentMethod;
use Obol\Provider\ProviderInterface;
use Obol\SubscriptionServiceInterface;
use Stripe\StripeClient;

class SubscriptionService implements SubscriptionServiceInterface
{
    protected StripeClient $stripe;

    protected Config $config;

    protected ProviderInterface $provider;

    /**
     * @param StripeClient $stripe
     */
    public function __construct(ProviderInterface $provider, Config $config, ?StripeClient $stripe = null)
    {
        $this->provider = $provider;
        $this->config = $config;
        $this->stripe = $stripe ?? new StripeClient($this->config->getApiKey());
    }

    public function updatePaymentMethod(UpdatePaymentMethod $updatePaymentMethod): void
    {
        $this->stripe->subscriptions->update($updatePaymentMethod->getSubscriptionId(), ['default_payment_method' => $updatePaymentMethod->getPaymentMethodReference()]);
    }

    public function list(int $limit = 10, ?string $lastId = null): array
    {
        $payload = ['limit' => $limit];
        if (isset($lastId) && !empty($lastId)) {
            $payload['starting_after'] = $lastId;
        }
        $result = $this->stripe->subscriptions->all($payload);
        $output = [];
        foreach ($result->data as $stripeSubscription) {
            foreach ($stripeSubscription->items as $stripeSubscriptionItem) {
                $output[] = $this->populateSubsscription($stripeSubscription, $stripeSubscriptionItem);
            }
        }

        return $output;
    }

    protected function populateSubsscription(\Stripe\Subscription $stripeSubscription, \Stripe\SubscriptionItem $subscriptionItem): Subscription
    {
        $money = Money::ofMinor($subscriptionItem->price->unit_amount, strtoupper($subscriptionItem->price->currency));
        $subscription = new Subscription();
        $subscription->setHasTrial(isset($stripeSubscription->trial_start));
        $subscription->setParentReference($stripeSubscription->id);
        $subscription->setLineId($subscriptionItem->id);
        $subscription->setPriceId($subscriptionItem->price->id);
        $subscription->setCostPerSeat($money);
        $subscription->setSeats($subscriptionItem->quantity);
        $subscription->setStoredPaymentReference($stripeSubscription->default_source);
        $subscription->setCustomerReference($stripeSubscription->customer);

        $createdAt = new \DateTime();
        $createdAt->setTimestamp($subscriptionItem->created);
        $subscription->setCreatedAt($createdAt);

        $validUntil = new \DateTime();
        $validUntil->setTimestamp($stripeSubscription->current_period_end);
        $subscription->setValidUntil($validUntil);

        return $subscription;
    }
}
