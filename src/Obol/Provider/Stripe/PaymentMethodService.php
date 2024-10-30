<?php

declare(strict_types=1);

/*
 * Copyright (C) 2020-2024 Iain Cambridge
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU LESSER GENERAL PUBLIC LICENSE as published by
 * the Free Software Foundation, either version 2.1 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Obol\Provider\Stripe;

use Obol\Model\PaymentMethod\PaymentMethodCard;
use Obol\PaymentMethodServiceInterface;
use Obol\Provider\ProviderInterface;
use Psr\Log\LoggerAwareTrait;
use Stripe\StripeClient;

class PaymentMethodService implements PaymentMethodServiceInterface
{
    use LoggerAwareTrait;

    protected StripeClient $stripe;

    protected Config $config;

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
