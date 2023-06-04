<?php

declare(strict_types=1);

/*
 * Copyright Iain Cambridge 2020-2023.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: TBD ( 3 years after 2.2.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
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
}
