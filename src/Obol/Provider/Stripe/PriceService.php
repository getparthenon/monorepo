<?php

declare(strict_types=1);

/*
 * Copyright (C) 2020-2025 Iain Cambridge
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

use Obol\Exception\ProviderFailureException;
use Obol\Model\CreatePrice;
use Obol\Model\Enum\AggregateType;
use Obol\Model\Enum\BillingType;
use Obol\Model\Enum\TierMode;
use Obol\Model\Enum\UsageType;
use Obol\Model\Metric;
use Obol\Model\Price;
use Obol\Model\PriceCreation;
use Obol\Model\Tier;
use Obol\PriceServiceInterface;
use Obol\Provider\ProviderInterface;
use Psr\Log\LoggerAwareTrait;
use Stripe\Billing\Meter;
use Stripe\StripeClient;
use Stripe\StripeObject;

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
        $payload['expand'] = ['data.tiers'];
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
        $price->setAggregateType(AggregateType::fromStripe($stripePrice->recurring?->aggregate_usage));
        $price->setTierMode(TierMode::fromString($stripePrice->tiers_mode));
        $price->setBillingType(BillingType::fromStripe($stripePrice->billing_scheme));
        $price->setUsageType(UsageType::fromStripe($stripePrice->recurring?->usage_type));
        $price->setPackageAmount($stripePrice->transform_quantity?->divide_by);

        if ($stripePrice->recurring?->meter) {
            /** @var Meter $stripeMeter */
            $stripeMeter = $this->stripe->billing->meters->retrieve($stripePrice->recurring->meter);
            $price->setMetric($this->populateMeter($stripeMeter));
        }

        $tiers = [];
        foreach ($stripePrice->tiers ?? [] as $tier) {
            $tiers[] = $this->populateTier($tier);
        }
        $price->setTiers($tiers);

        return $price;
    }

    public function populateTier(StripeObject $data): Tier
    {
        $tier = new Tier();
        $tier->setUpTo($data->up_to);
        $tier->setFlatAmount($data->flat_amount);
        $tier->setUnitAmount($data->unit_amount);

        return $tier;
    }

    public function populateMeter(Meter $data): Metric
    {
        $metric = new Metric();
        $metric->setAggregation($data->default_aggregation->formula);
        $metric->setDisplayName($data->display_name);
        $metric->setEventName($data->event_name);
        $metric->setEventTimeWindow($data->event_time_window);

        return $metric;
    }
}
