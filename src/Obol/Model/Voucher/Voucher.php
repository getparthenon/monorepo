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

namespace Obol\Model\Voucher;

class Voucher
{
    private ?string $id = null;

    private ?string $code = null;

    private string $type;

    private array $amounts;

    private string $duration;

    private ?int $durationInMonths = null;

    private string $name;

    private null|int|float $percentage;

    private ?\DateTime $createdAt;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getDuration(): string
    {
        return $this->duration;
    }

    public function setDuration(string $duration): void
    {
        $this->duration = $duration;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPercentage(): null|int|float
    {
        return $this->percentage;
    }

    public function setPercentage(null|int|float $percentage): void
    {
        $this->percentage = $percentage;
    }

    public function getDurationInMonths(): ?int
    {
        return $this->durationInMonths;
    }

    public function setDurationInMonths(?int $durationInMonths): void
    {
        $this->durationInMonths = $durationInMonths;
    }

    /**
     * @return Amount[]
     */
    public function getAmounts(): array
    {
        return $this->amounts;
    }

    public function setAmounts(array $amounts): void
    {
        $this->amounts = $amounts;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
