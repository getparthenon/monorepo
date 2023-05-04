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

namespace Obol\Provider\Stripe;

use Obol\ChargeBackServiceInterface;
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
use Obol\WebhookServiceInterface;
use Stripe\StripeClient;

class Provider implements ProviderInterface
{
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
    ) {
        $this->config = $config;
        $this->stripeClient = $stripe ?? new StripeClient($this->config->getApiKey());
        $this->paymentService = $paymentService ?? new PaymentService($this, $config, $this->stripeClient);
        $this->hostedCheckoutService = $hostedCheckoutService ?? new \Obol\Provider\Stripe\HostedCheckoutService($this, $config, $this->stripeClient);
        $this->customerService = $customerService ?? new CustomerService($this, $config, $this->stripeClient);
        $this->priceService = $priceService ?? new PriceService($this, $config, $this->stripeClient);
        $this->productService = $productService ?? new ProductService($this, $config, $this->stripeClient);
        $this->refundService = $refundService ?? new RefundService($this, $config, $this->stripeClient);
        $this->subscriptionService = $subscriptionService ?? new SubscriptionService($this, $config, $this->stripeClient);
        $this->webhookService = $webhookService ?? new WebhookService($this, $config, $this->stripeClient);
        $this->invoiceService = $invoiceService ?? new InvoiceService($this, $config, $this->stripeClient);
        $this->chargeBackService = $chargeBackService ?? new ChargeBackService($this, $config, $this->stripeClient);
        $this->paymentMethodService = $paymentMethodService ?? new PaymentMethodService($this, $config, $this->stripeClient);
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
        return $this->customerService;
    }

    public function prices(): PriceServiceInterface
    {
        return $this->priceService;
    }

    public function products(): ProductServiceInterface
    {
        return $this->productService;
    }

    public function refunds(): RefundServiceInterface
    {
        return $this->refundService;
    }

    public function subscriptions(): SubscriptionServiceInterface
    {
        return $this->subscriptionService;
    }

    public function webhook(): WebhookServiceInterface
    {
        return $this->webhookService;
    }

    public function invoices(): InvoiceServiceInterface
    {
        return $this->invoiceService;
    }

    public function chargeBacks(): ChargeBackServiceInterface
    {
        return $this->chargeBackService;
    }

    public function paymentMethods(): PaymentMethodServiceInterface
    {
        return $this->paymentMethodService;
    }
}
