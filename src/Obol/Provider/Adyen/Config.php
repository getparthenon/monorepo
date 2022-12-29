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
