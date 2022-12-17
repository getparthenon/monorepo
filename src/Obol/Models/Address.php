<?php

declare(strict_types=1);

/*
 * Copyright Iain Cambridge 2020-2022.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: TBD ( 3 years after 2.2.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace Obol\Models;

class Address
{
    protected string $streetLineOne;
    protected string $streetLineTwo;
    protected string $city;
    protected string $state;
    protected string $countryCode;
    protected string $postalCode;

    public function getStreetLineOne(): string
    {
        return $this->streetLineOne;
    }

    public function setStreetLineOne(string $streetLineOne): self
    {
        $this->streetLineOne = $streetLineOne;

        return $this;
    }

    public function getStreetLineTwo(): string
    {
        return $this->streetLineTwo;
    }

    public function setStreetLineTwo(string $streetLineTwo): self
    {
        $this->streetLineTwo = $streetLineTwo;

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }
}
