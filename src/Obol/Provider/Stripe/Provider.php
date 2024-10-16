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

namespace Obol\Provider\Stripe;

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
use Psr\Log\LoggerAwareTrait;
use Stripe\StripeClient;

class Provider implements ProviderInterface
{
    use LoggerAwareTrait;

    public const NAME = 'stripe';
    private PaymentServiceInterface $paymentService;
    private PriceServiceInterface $priceService;
    private HostedCheckoutServiceInterface $hostedCheckoutService;
    private CustomerServiceInterface $customerService;
    private ProductServiceInterface $productService;
    private RefundServiceInterface $refundService;
    private SubscriptionServiceInterface $subscriptionService;
    private WebhookServiceInterface $webhookService;
    private InvoiceServiceInterface $invoiceService;
    private ChargeBackServiceInterface $chargeBackService;
    private PaymentMethodServiceInterface $paymentMethodService;
    private CreditServiceInterface $creditService;
    private VoucherServiceInterface $voucherService;
    private StripeClient $stripeClient;
    private Config $config;

    public function __construct(
        Config $config,
        ?StripeClient $stripe = null,
        ?PaymentServiceInterface $paymentService = null,
        ?HostedCheckoutServiceInterface $hostedCheckoutService = null,
        ?CustomerServiceInterface $customerService = null,
        ?PriceServiceInterface $priceService = null,
        ?ProductServiceInterface $productService = null,
        ?RefundService $refundService = null,
        ?SubscriptionServiceInterface $subscriptionService = null,
        ?SubscriptionServiceInterface $webhookService = null,
        ?InvoiceServiceInterface $invoiceService = null,
        ?ChargeBackServiceInterface $chargeBackService = null,
        ?PaymentMethodServiceInterface $paymentMethodService = null,
        ?CreditServiceInterface $creditService = null,
        ?VoucherServiceInterface $voucherService = null,
    ) {
        $this->config = $config;
        $this->stripeClient = $stripe ?? new StripeClient($this->config->getApiKey());
        $this->paymentService = $paymentService ?? new PaymentService($this, $config, $this->stripeClient);
        $this->hostedCheckoutService = $hostedCheckoutService ?? new HostedCheckoutService($this, $config, $this->stripeClient);
        $this->customerService = $customerService ?? new CustomerService($this, $config, $this->stripeClient);
        $this->priceService = $priceService ?? new PriceService($this, $config, $this->stripeClient);
        $this->productService = $productService ?? new ProductService($this, $config, $this->stripeClient);
        $this->refundService = $refundService ?? new RefundService($this, $config, $this->stripeClient);
        $this->subscriptionService = $subscriptionService ?? new SubscriptionService($this, $config, $this->stripeClient);
        $this->webhookService = $webhookService ?? new WebhookService($this, $config, $this->stripeClient);
        $this->invoiceService = $invoiceService ?? new InvoiceService($this, $config, $this->stripeClient);
        $this->chargeBackService = $chargeBackService ?? new ChargeBackService($this, $config, $this->stripeClient);
        $this->paymentMethodService = $paymentMethodService ?? new PaymentMethodService($this, $config, $this->stripeClient);
        $this->creditService = $creditService ?? new CreditService($this, $config, $this->stripeClient);
        $this->voucherService = $voucherService ?? new VoucherService($this, $config, $this->stripeClient);
    }

    public function payments(): PaymentServiceInterface
    {
        if ($this->logger) {
            $this->paymentService->setLogger($this->logger);
        }

        return $this->paymentService;
    }

    public function hostedCheckouts(): HostedCheckoutServiceInterface
    {
        if ($this->logger) {
            $this->hostedCheckoutService->setLogger($this->logger);
        }

        return $this->hostedCheckoutService;
    }

    public function getName(): string
    {
        return static::NAME;
    }

    public function customers(): CustomerServiceInterface
    {
        if ($this->logger) {
            $this->customerService->setLogger($this->logger);
        }

        return $this->customerService;
    }

    public function prices(): PriceServiceInterface
    {
        if ($this->logger) {
            $this->priceService->setLogger($this->logger);
        }

        return $this->priceService;
    }

    public function products(): ProductServiceInterface
    {
        if ($this->logger) {
            $this->productService->setLogger($this->logger);
        }

        return $this->productService;
    }

    public function refunds(): RefundServiceInterface
    {
        if ($this->logger) {
            $this->refundService->setLogger($this->logger);
        }

        return $this->refundService;
    }

    public function subscriptions(): SubscriptionServiceInterface
    {
        if ($this->logger) {
            $this->subscriptionService->setLogger($this->logger);
        }

        return $this->subscriptionService;
    }

    public function webhook(): WebhookServiceInterface
    {
        if ($this->logger) {
            $this->webhookService->setLogger($this->logger);
        }

        return $this->webhookService;
    }

    public function invoices(): InvoiceServiceInterface
    {
        if ($this->logger) {
            $this->invoiceService->setLogger($this->logger);
        }

        return $this->invoiceService;
    }

    public function chargeBacks(): ChargeBackServiceInterface
    {
        if ($this->logger) {
            $this->chargeBackService->setLogger($this->logger);
        }

        return $this->chargeBackService;
    }

    public function paymentMethods(): PaymentMethodServiceInterface
    {
        if ($this->logger) {
            $this->paymentMethodService->setLogger($this->logger);
        }

        return $this->paymentMethodService;
    }

    public function credit(): CreditServiceInterface
    {
        if ($this->logger) {
            $this->creditService->setLogger($this->logger);
        }

        return $this->creditService;
    }

    public function vouchers(): VoucherServiceInterface
    {
        if ($this->logger) {
            $this->voucherService->setLogger($this->logger);
        }

        return $this->voucherService;
    }

    public function isLive(): bool
    {
        \Stripe\Stripe::setApiKey($this->config->getApiKey());
        $account = \Stripe\Account::retrieve();

        // Check if the API key is live mode or test mode
        return $account->livemode;
    }
}
