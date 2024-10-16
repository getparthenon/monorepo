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

namespace Obol\Provider\Adyen;

use Obol\ChargeBackServiceInterface;
use Obol\CreditServiceInterface;
use Obol\CustomerServiceInterface;
use Obol\Exception\UnsupportedFunctionalityException;
use Obol\HostedCheckoutServiceInterface;
use Obol\InvoiceServiceInterface;
use Obol\PaymentMethodServiceInterface;
use Obol\PaymentServiceInterface;
use Obol\PriceServiceInterface;
use Obol\ProductServiceInterface;
use Obol\Provider\ProviderInterface;
use Obol\RefundServiceInterface;
use Obol\SubscriptionServiceInterface;
use Obol\VoucherServiceInterface;
use Obol\WebhookServiceInterface;

class Provider implements ProviderInterface
{
    public const NAME = 'adyen';

    public function __construct(private PaymentServiceInterface $paymentService, private HostedCheckoutServiceInterface $hostedCheckoutService)
    {
    }

    public function payments(): PaymentServiceInterface
    {
        return $this->paymentService;
    }

    public function hostedCheckouts(): HostedCheckoutServiceInterface
    {
        return $this->hostedCheckoutService;
    }

    public function getName(): string
    {
        return static::NAME;
    }

    public function customers(): CustomerServiceInterface
    {
        throw new UnsupportedFunctionalityException();
    }

    public function prices(): PriceServiceInterface
    {
        return new PriceService();
    }

    public function products(): ProductServiceInterface
    {
        // TODO: Implement products() method.
    }

    public function refunds(): RefundServiceInterface
    {
        return new RefundService();
    }

    public function subscriptions(): SubscriptionServiceInterface
    {
        throw new UnsupportedFunctionalityException();
    }

    public function webhook(): WebhookServiceInterface
    {
        // TODO: Implement webhook() method.
    }

    public function invoices(): InvoiceServiceInterface
    {
        // TODO: Implement invoices() method.
    }

    public function chargeBacks(): ChargeBackServiceInterface
    {
        // TODO: Implement chargeBacks() method.
    }

    public function paymentMethods(): PaymentMethodServiceInterface
    {
        // TODO: Implement paymentMethods() method.
    }

    public function credit(): CreditServiceInterface
    {
        // TODO: Implement credit() method.
    }

    public function vouchers(): VoucherServiceInterface
    {
        // TODO: Implement vouchers() method.
    }

    public function isLive(): bool
    {
        return false;
    }
}
