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

namespace Obol\Model;

class CardOnFileResponse
{
    protected CardFile $cardFile;

    protected ?CustomerCreation $customerCreation = null;

    public function getCardFile(): CardFile
    {
        return $this->cardFile;
    }

    public function setCardFile(CardFile $cardFile): void
    {
        $this->cardFile = $cardFile;
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
