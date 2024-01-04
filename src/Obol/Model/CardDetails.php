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

class CardDetails
{
    protected string $number;
    protected string $expireDate;
    protected string $expireYear;
    protected string $securityCode;
    protected string $name;
    protected string $token;

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getExpireDate(): string
    {
        return $this->expireDate;
    }

    public function setExpireDate(string $expireDate): static
    {
        $this->expireDate = $expireDate;

        return $this;
    }

    public function getExpireYear(): string
    {
        return $this->expireYear;
    }

    public function setExpireYear(string $expireYear): static
    {
        $this->expireYear = $expireYear;

        return $this;
    }

    public function getSecurityCode(): string
    {
        return $this->securityCode;
    }

    public function setSecurityCode(string $securityCode): static
    {
        $this->securityCode = $securityCode;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function hasToken(): bool
    {
        return isset($this->token);
    }
}
