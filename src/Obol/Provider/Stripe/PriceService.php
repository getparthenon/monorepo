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

use Obol\Exception\ProviderFailureException;
use Obol\Model\CreatePrice;
use Obol\Model\PriceCreation;
use Obol\PriceServiceInterface;
use Obol\Provider\ProviderInterface;
use Stripe\StripeClient;

class PriceService implements PriceServiceInterface
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

    public function createPrice(CreatePrice $createPrice): PriceCreation
    {
        $payload = [
            'unit_amount' => $createPrice->getMoney()->getUnscaledAmount(),
            'currency' => $createPrice->getMoney()->getCurrency()->getCurrencyCode(),
            'product' => $createPrice->getProductReference(),
            'tax_behavior' => $createPrice->isIncludingTax() ? 'inclusive' : 'exclusive',
        ];

        if ($createPrice->isRecurring()) {
            $payload['recurring'] = ['interval' => $createPrice->getPaymentSchedule()];
        }

        try {
            $result = $this->stripe->prices->create($payload);
        } catch (\Throwable $exception) {
            throw new ProviderFailureException(previous: $exception);
        }
        if (true === $result->livemode) {
            $url = sprintf('https://dashboard.stripe.com/prices/%s', $result->id);
        } else {
            $url = sprintf('https://dashboard.stripe.com/test/prices/%s', $result->id);
        }

        $priceCreation = new PriceCreation();
        $priceCreation->setReference($result->id);
        $priceCreation->setDetailsUrl($url);

        return $priceCreation;
    }
}
