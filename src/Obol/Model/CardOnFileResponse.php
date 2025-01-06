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
