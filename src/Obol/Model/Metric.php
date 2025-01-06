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

namespace Obol\Model;

class Metric
{
    private string $displayName;

    private string $eventName;

    private string $aggregation;

    private ?string $eventTimeWindow = null;

    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): void
    {
        $this->displayName = $displayName;
    }

    public function getEventName(): string
    {
        return $this->eventName;
    }

    public function setEventName(string $eventName): void
    {
        $this->eventName = $eventName;
    }

    public function getAggregation(): string
    {
        return $this->aggregation;
    }

    public function setAggregation(string $aggregation): void
    {
        $this->aggregation = $aggregation;
    }

    public function getEventTimeWindow(): ?string
    {
        return $this->eventTimeWindow;
    }

    public function setEventTimeWindow(?string $eventTimeWindow): void
    {
        $this->eventTimeWindow = $eventTimeWindow;
    }
}
