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

    private int|float|null $percentage;

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

    public function getPercentage(): int|float|null
    {
        return $this->percentage;
    }

    public function setPercentage(int|float|null $percentage): void
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
