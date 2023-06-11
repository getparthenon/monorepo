<?php

declare(strict_types=1);

/*
 * Copyright Humbly Arrogant Software Limited 2020-2023.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: TBD ( 3 years after 2.2.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace Obol\Model;

// TODO rename
class FrontendCardProcess
{
    protected string $token;

    protected string $customerReference;

    protected ?CustomerCreation $customerCreation;

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getCustomerReference(): string
    {
        return $this->customerReference;
    }

    public function setCustomerReference(string $customerReference): void
    {
        $this->customerReference = $customerReference;
    }

    public function getCustomerCreation(): ?CustomerCreation
    {
        return $this->customerCreation;
    }

    public function setCustomerCreation(?CustomerCreation $customerCreation): void
    {
        $this->customerCreation = $customerCreation;
    }

    public function hasCustomerCreation(): bool
    {
        return isset($this->customerCreation);
    }
}
