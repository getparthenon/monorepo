<?php

declare(strict_types=1);

/*
 * Copyright (C) 2020-2024 Iain Cambridge
 *
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Obol\Model;

class Address
{
    protected ?string $streetLineOne = null;
    protected ?string $streetLineTwo = null;
    protected ?string $city = null;
    protected ?string $state = null;
    protected ?string $countryCode = null;
    protected ?string $postalCode = null;

    public function getStreetLineOne(): ?string
    {
        return $this->streetLineOne;
    }

    public function setStreetLineOne(?string $streetLineOne): self
    {
        $this->streetLineOne = $streetLineOne;

        return $this;
    }

    public function getStreetLineTwo(): ?string
    {
        return $this->streetLineTwo;
    }

    public function setStreetLineTwo(?string $streetLineTwo): self
    {
        $this->streetLineTwo = $streetLineTwo;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(?string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function hasCountryCode(): bool
    {
        return null !== $this->countryCode;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }
}
