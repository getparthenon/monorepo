<?php

declare(strict_types=1);

/*
 * Copyright Humbly Arrogant Software Limited 2020-2023.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: 26.06.2026 ( 3 years after 2.2.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
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
