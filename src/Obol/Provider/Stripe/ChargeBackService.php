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

use Obol\ChargeBackServiceInterface;
use Obol\Model\ChargeBack\ChargeBack;
use Obol\Provider\ProviderInterface;
use Stripe\StripeClient;

class ChargeBackService implements ChargeBackServiceInterface
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

    public function createdSinceYesterday(): array
    {
        $now = new \DateTime('-24 hours', new \DateTimeZone('UTC'));

        $lastId = null;
        $output = [];

        do {
            $stripeResult = $this->stripe->disputes->all(['created' => ['gte' => $now->getTimestamp()], 'starting_after' => $lastId]);

            foreach ($stripeResult->data as $stripeChargeBack) {
                $chargeBack = new ChargeBack();
                $chargeBack->setId($stripeChargeBack->id);
                $chargeBack->setAmount($stripeChargeBack->amount);
                $chargeBack->setCurrency($stripeChargeBack->currency);
                $chargeBack->setPaymentReference($stripeChargeBack->charge);
                $chargeBack->setStatus($stripeChargeBack->status);
                $chargeBack->setReason($stripeChargeBack->reason);

                $output[] = $chargeBack;
                $lastId = $stripeChargeBack->id;
            }
        } while ($stripeResult->has_more);

        return $output;
    }
}