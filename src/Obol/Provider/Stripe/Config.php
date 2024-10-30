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

namespace Obol\Provider\Stripe;

use Obol\Exception\MissingConfigFieldException;

class Config
{
    protected array $paymentMethods = ['card'];
    private bool $pciMode = false;

    private string $apiKey;

    private string $successUrl;

    private string $cancelUrl;

    public function isPciMode(): bool
    {
        return $this->pciMode;
    }

    public function setPciMode(bool $pciMode): static
    {
        $this->pciMode = $pciMode;

        return $this;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): static
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function getSuccessUrl(): string
    {
        if (isset($this->successUrl)) {
            throw new MissingConfigFieldException('success_url needs to be configured');
        }

        return $this->successUrl;
    }

    public function setSuccessUrl(string $successUrl): static
    {
        $this->successUrl = $successUrl;

        return $this;
    }

    public function getCancelUrl(): string
    {
        if (isset($this->cancelUrl)) {
            throw new MissingConfigFieldException('cancel_url needs to be configured');
        }

        return $this->cancelUrl;
    }

    public function setCancelUrl(string $cancelUrl): static
    {
        $this->cancelUrl = $cancelUrl;

        return $this;
    }

    /**
     * @return []string
     */
    public function getPaymentMethods(): array
    {
        return $this->paymentMethods;
    }

    public function setPaymentMethods(array $paymentMethods): static
    {
        $this->paymentMethods = $paymentMethods;

        return $this;
    }
}
