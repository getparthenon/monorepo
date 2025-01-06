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

namespace Obol\Provider\Adyen;

use Obol\Exception\MissingConfigFieldException;

class Config
{
    protected string $apiKey;

    protected string $merchantAccount;

    protected bool $testMode;

    protected bool $pciMode;

    protected string $returnUrl;

    protected string $prefix;

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): static
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function isTestMode(): bool
    {
        return $this->testMode;
    }

    public function setTestMode(bool $testMode): static
    {
        $this->testMode = $testMode;

        return $this;
    }

    public function getMerchantAccount(): string
    {
        return $this->merchantAccount;
    }

    public function setMerchantAccount(string $merchantAccount): static
    {
        $this->merchantAccount = $merchantAccount;

        return $this;
    }

    public function getReturnUrl(): string
    {
        return $this->returnUrl;
    }

    public function setReturnUrl(string $returnUrl): static
    {
        $this->returnUrl = $returnUrl;

        return $this;
    }

    public function isPciMode(): bool
    {
        return $this->pciMode;
    }

    public function setPciMode(bool $pciMode): static
    {
        $this->pciMode = $pciMode;

        return $this;
    }

    public function getPrefix(): string
    {
        if (!isset($this->prefix)) {
            throw new MissingConfigFieldException('prefix must be set');
        }

        return $this->prefix;
    }

    public function setPrefix(string $prefix): void
    {
        $this->prefix = $prefix;
    }
}
