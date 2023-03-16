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

use Obol\CustomerServiceInterface;
use Obol\HostedCheckoutServiceInterface;
use Obol\PaymentServiceInterface;
use Obol\Provider\ProviderInterface;
use Stripe\StripeClient;

class Provider implements ProviderInterface
{
    public const NAME = 'stripe';
    private PaymentServiceInterface $paymentService;
    private HostedCheckoutServiceInterface $hostedCheckoutService;
    private CustomerServiceInterface $customerService;
    private StripeClient $stripeClient;
    private Config $config;

    public function __construct(
        Config $config,
        ?StripeClient $stripe = null,
        ?PaymentServiceInterface $paymentService = null,
        ?HostedCheckoutServiceInterface $hostedCheckoutService = null,
        ?CustomerServiceInterface $customerService = null,
    ) {
        $this->config = $config;
        $this->stripeClient = $stripe ?? new StripeClient($this->config->getApiKey());
        $this->paymentService = $paymentService ?? new PaymentService($this, $config, $this->stripeClient);
        $this->hostedCheckoutService = $hostedCheckoutService ?? new \Obol\Provider\Stripe\HostedCheckoutService($this, $config, $this->stripeClient);
        $this->customerService = $customerService ?? new CustomerService($this, $config, $this->stripeClient);
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
}
