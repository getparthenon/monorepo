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

namespace Obol;

use Obol\Model\Credit\BalanceOutput;
use Obol\Model\Credit\CreditTransaction;

interface CreditServiceInterface
{
    public function addCreditTransaction(CreditTransaction $creditTransaction): BalanceOutput;

    /**
     * @returns BalanceOutput[]
     */
    public function getAllForCustomer(string $customerId, int $limit = 10, ?string $lastId = null): array;
}
