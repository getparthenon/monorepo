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

namespace App\Dummy\Provider;

use Obol\ChargeBackServiceInterface;
use Obol\CreditServiceInterface;
use Obol\CustomerServiceInterface;
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
    public function payments(): PaymentServiceInterface
    {
        return new PaymentService();
    }

    public function hostedCheckouts(): HostedCheckoutServiceInterface
    {
        return new HostedCheckoutService();
    }

    public function customers(): CustomerServiceInterface
    {
        return new CustomerService();
    }

    public function prices(): PriceServiceInterface
    {
        return new PriceService();
    }

    public function products(): ProductServiceInterface
    {
        return new ProductService();
    }

    public function refunds(): RefundServiceInterface
    {
        return new RefundService();
    }

    public function getName(): string
    {
        return 'test_dummy';
    }

    public function subscriptions(): SubscriptionServiceInterface
    {
        return new SubscriptionService();
    }

    public function webhook(): WebhookServiceInterface
    {
        return new WebhookService();
    }

    public function invoices(): InvoiceServiceInterface
    {
        return new InvoiceService();
    }

    public function chargeBacks(): ChargeBackServiceInterface
    {
        return new ChargeBackService();
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
        return true;
    }
}
