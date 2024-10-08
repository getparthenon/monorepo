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

namespace Obol\Model;

use Obol\Model\Enum\AggregateType;
use Obol\Model\Enum\BillingType;
use Obol\Model\Enum\TierMode;
use Obol\Model\Enum\UsageType;

class Price
{
    protected ?string $id = null;

    protected ?string $url = null;

    private ?int $amount;

    private string $currency;

    private bool $includingTax;

    private string $productReference;

    private bool $recurring;

    private ?string $schedule = null;

    private ?TierMode $tierMode = null;

    private ?BillingType $billingType = null;

    private ?AggregateType $aggregateType = null;

    private ?UsageType $usageType = null;

    private array $tiers = [];

    private ?Metric $metric = null;

    private ?int $packageAmount = null;

    public function isIncludingTax(): bool
    {
        return $this->includingTax;
    }

    public function setIncludingTax(bool $includingTax): void
    {
        $this->includingTax = $includingTax;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(?int $amount): void
    {
        $this->amount = $amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    public function getProductReference(): string
    {
        return $this->productReference;
    }

    public function setProductReference(string $productReference): void
    {
        $this->productReference = $productReference;
    }

    public function isRecurring(): bool
    {
        return $this->recurring;
    }

    public function setRecurring(bool $recurring): void
    {
        $this->recurring = $recurring;
    }

    public function getSchedule(): ?string
    {
        return $this->schedule;
    }

    public function setSchedule(?string $schedule): void
    {
        $this->schedule = $schedule;
    }

    public function getTierMode(): ?TierMode
    {
        return $this->tierMode;
    }

    public function setTierMode(?TierMode $tierMode): void
    {
        $this->tierMode = $tierMode;
    }

    public function getBillingType(): ?BillingType
    {
        return $this->billingType;
    }

    public function setBillingType(?BillingType $billingType): void
    {
        $this->billingType = $billingType;
    }

    public function getAggregateType(): ?AggregateType
    {
        return $this->aggregateType;
    }

    public function setAggregateType(?AggregateType $aggregateType): void
    {
        $this->aggregateType = $aggregateType;
    }

    public function getUsageType(): ?UsageType
    {
        return $this->usageType;
    }

    public function setUsageType(?UsageType $usageType): void
    {
        $this->usageType = $usageType;
    }

    public function getTiers(): array
    {
        return $this->tiers;
    }

    public function setTiers(array $tiers): void
    {
        $this->tiers = $tiers;
    }

    public function getMetric(): ?Metric
    {
        return $this->metric;
    }

    public function setMetric(?Metric $metric): void
    {
        $this->metric = $metric;
    }

    public function getPackageAmount(): ?int
    {
        return $this->packageAmount;
    }

    public function setPackageAmount(?int $packageAmount): void
    {
        $this->packageAmount = $packageAmount;
    }
}
