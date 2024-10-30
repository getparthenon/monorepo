<?php

declare(strict_types=1);

/*
 * Copyright (C) 2020-2024 Iain Cambridge
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
