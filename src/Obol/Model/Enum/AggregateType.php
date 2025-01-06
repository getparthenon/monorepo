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

namespace Obol\Model\Enum;

enum AggregateType
{
    case LAST_DURING_PERIOD;
    case LAST_EVER;
    case MAX;
    case SUM;

    public static function fromStripe(?string $value): ?AggregateType
    {
        return match ($value) {
            'last_during_period' => self::LAST_DURING_PERIOD,
            'last_ever' => self::LAST_EVER,
            'max' => self::MAX,
            'sum', 'metered' => self::SUM,
            default => null,
        };
    }
}
