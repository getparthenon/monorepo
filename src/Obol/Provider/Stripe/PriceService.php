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

use Obol\Exception\ProviderFailureException;
use Obol\Model\CreatePrice;
use Obol\Model\Price;
use Obol\Model\PriceCreation;
use Obol\PriceServiceInterface;
use Obol\Provider\ProviderInterface;
use Psr\Log\LoggerAwareTrait;
use Stripe\StripeClient;

class PriceService implements PriceServiceInterface
{
    use LoggerAwareTrait;

    protected StripeClient $stripe;

    protected Config $config;

    protected ProviderInterface $provider;

    public function __construct(ProviderInterface $provider, Config $config, ?StripeClient $stripe = null)
    {
        $this->provider = $provider;
        $this->config = $config;
        $this->stripe = $stripe ?? new StripeClient($this->config->getApiKey());
    }

    public function createPrice(CreatePrice $createPrice): PriceCreation
    {
        $payload = [
            'currency' => $createPrice->getMoney()->getCurrency()->getCurrencyCode(),
            'product' => $createPrice->getProductReference(),
            'tax_behavior' => $createPrice->isIncludingTax() ? 'inclusive' : 'exclusive',
        ];

        $payload['unit_amount'] = $createPrice->getMoney()->getMinorAmount()->toInt();

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

    public function fetch(string $priceId): Price
    {
        $stripePrice = $this->stripe->prices->retrieve($priceId);

        $price = $this->populatePrice($stripePrice);

        return $price;
    }

    public function list(int $limit = 10, ?string $lastId = null): array
    {
        $payload = ['limit' => $limit];
        if (isset($lastId) && !empty($lastId)) {
            $payload['starting_after'] = $lastId;
        }
        $result = $this->stripe->prices->all($payload);
        $output = [];

        foreach ($result->data as $stripePrice) {
            $output[] = $this->populatePrice($stripePrice);
        }

        return $output;
    }

    public function populatePrice(\Stripe\Price $stripePrice): Price
    {
        if (true === $stripePrice->livemode) {
            $url = sprintf('https://dashboard.stripe.com/prices/%s', $stripePrice->id);
        } else {
            $url = sprintf('https://dashboard.stripe.com/test/prices/%s', $stripePrice->id);
        }

        $price = new Price();
        $price->setId($stripePrice->id);
        $price->setAmount($stripePrice->unit_amount);
        $price->setCurrency($stripePrice->currency);
        $price->setUrl($url);
        $price->setProductReference($stripePrice->product);
        $price->setRecurring(isset($stripePrice->recurring?->interval));
        $price->setSchedule($stripePrice->recurring?->interval);
        $price->setIncludingTax('inclusive' === $stripePrice->tax_behavior);

        return $price;
    }
}
