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

enum ChargeFailureReasons: string
{
    case EXPIRED_CARD = 'expired_card';
    case INVALID_DETAILS = 'invalid_details';
    case FRAUD = 'fraud';
    case AUTHENTICATION_REQUIRED = 'authentication_required';
    case INVALID_CARD = 'invalid_card';
    case GENERAL_DECLINE = 'general_decline';
    case CONTACT_PROVIDER = 'contact_provider';
    case LACK_OF_FUNDS = 'lack_of_funds';
}
