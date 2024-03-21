<?php

declare(strict_types=1);

/*
 * Copyright (C) 2020-2024 Iain Cambridge
 *
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Obol\Provider\Stripe;

use Obol\ChargeBackServiceInterface;
use Obol\Model\ChargeBack\ChargeBack;
use Obol\Provider\ProviderInterface;
use Stripe\Dispute;
use Stripe\StripeClient;

class ChargeBackService implements ChargeBackServiceInterface
{
    protected StripeClient $stripe;

    protected Config $config;

    public function __construct(private ProviderInterface $provider, Config $config, ?StripeClient $stripe = null)
    {
        $this->config = $config;
        $this->stripe = $stripe ?? new StripeClient($this->config->getApiKey());
    }

    public function createdSinceYesterday(): array
    {
        $now = new \DateTime('-24 hours', new \DateTimeZone('UTC'));

        $lastId = null;
        $output = [];

        do {
            $stripeResult = $this->stripe->disputes->all(['created' => ['gte' => $now->getTimestamp()], 'starting_after' => $lastId]);

            foreach ($stripeResult->data as $stripeChargeBack) {
                $chargeBack = $this->populateChargeBack($stripeChargeBack);

                $output[] = $chargeBack;
                $lastId = $stripeChargeBack->id;
            }
        } while ($stripeResult->has_more);

        return $output;
    }

    public function list(int $limit, ?string $lastId = null): array
    {
        $payload = ['limit' => $limit];
        if (isset($lastId) && !empty($lastId)) {
            $payload['starting_after'] = $lastId;
        }

        $stripeResult = $this->stripe->disputes->all($payload);
        $output = [];
        foreach ($stripeResult->data as $stripeChargeBack) {
            $chargeBack = $this->populateChargeBack($stripeChargeBack);

            $output[] = $chargeBack;
        }

        return $output;
    }

    public function populateChargeBack(Dispute $stripeChargeBack): ChargeBack
    {
        $chargeBack = new ChargeBack();
        $chargeBack->setId($stripeChargeBack->id);
        $chargeBack->setAmount($stripeChargeBack->amount);
        $chargeBack->setCurrency($stripeChargeBack->currency);
        $chargeBack->setPaymentReference($stripeChargeBack->charge);
        $chargeBack->setStatus($stripeChargeBack->status);
        $chargeBack->setReason($stripeChargeBack->reason);

        $createdAt = new \DateTime();
        $createdAt->setTimestamp($stripeChargeBack->created);
        $chargeBack->setCreatedAt($createdAt);

        return $chargeBack;
    }
}
