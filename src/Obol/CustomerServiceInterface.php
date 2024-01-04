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

namespace Obol;

use Obol\Exception\ProviderFailureException;
use Obol\Model\Customer;
use Obol\Model\CustomerCreation;

interface CustomerServiceInterface
{
    /**
     * @throws ProviderFailureException
     */
    public function create(Customer $customer): CustomerCreation;

    public function update(Customer $customer): bool;

    public function fetch(string $customerId): Customer;

    /**
     * @return Customer[]
     */
    public function list(int $limit = 10, ?string $lastId = null): array;

    public function getCards(string $customerId, int $limit = 10, ?string $lastId = null): array;
}
