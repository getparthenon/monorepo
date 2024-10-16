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

namespace Obol\Provider;

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
use Obol\RefundServiceInterface;
use Obol\SubscriptionServiceInterface;
use Obol\VoucherServiceInterface;
use Obol\WebhookServiceInterface;

interface ProviderInterface
{
    /**
     * @throws UnsupportedFunctionalityException
     */
    public function payments(): PaymentServiceInterface;

    // Billing System
    /**
     * @throws UnsupportedFunctionalityException
     */
    public function hostedCheckouts(): HostedCheckoutServiceInterface;

    /**
     * @throws UnsupportedFunctionalityException
     */
    public function customers(): CustomerServiceInterface;

    // Billing System
    /**
     * @throws UnsupportedFunctionalityException
     */
    public function prices(): PriceServiceInterface;

    // Billing System
    /**
     * @throws UnsupportedFunctionalityException
     */
    public function products(): ProductServiceInterface;

    /**
     * @throws UnsupportedFunctionalityException
     */
    public function refunds(): RefundServiceInterface;

    // Billing System
    public function subscriptions(): SubscriptionServiceInterface;

    public function webhook(): WebhookServiceInterface;

    // Billing System
    public function invoices(): InvoiceServiceInterface;

    public function chargeBacks(): ChargeBackServiceInterface;

    public function paymentMethods(): PaymentMethodServiceInterface;

    public function credit(): CreditServiceInterface;

    public function vouchers(): VoucherServiceInterface;

    public function getName(): string;

    public function isLive(): bool;
}
