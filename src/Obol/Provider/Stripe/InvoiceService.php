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

use Obol\InvoiceServiceInterface;
use Obol\Model\Invoice\Invoice;
use Obol\Model\Invoice\InvoiceLine;
use Obol\Provider\ProviderInterface;
use Stripe\StripeClient;

class InvoiceService implements InvoiceServiceInterface
{
    protected StripeClient $stripe;

    protected Config $config;

    protected ProviderInterface $provider;

    /**
     * @param StripeClient $stripe
     */
    public function __construct(ProviderInterface $provider, Config $config, ?StripeClient $stripe = null)
    {
        $this->provider = $provider;
        $this->config = $config;
        $this->stripe = $stripe ?? new StripeClient($this->config->getApiKey());
    }

    public function fetch(string $id): Invoice
    {
        $stripeInvoice = $this->stripe->invoices->retrieve($id);

        $output = new Invoice();
        $output->setCurrency($stripeInvoice->currency);
        $output->setCustomerReference($stripeInvoice->customer);
        $output->setTotal($stripeInvoice->total);
        $output->setAmountDue($stripeInvoice->amount_due);
        $output->setAmountPaid($stripeInvoice->amount_paid);

        foreach ($stripeInvoice->lines->data as $originalLine) {
            $invoiceLine = new InvoiceLine();
            $invoiceLine->setId($originalLine->id);
            $invoiceLine->setAmount($originalLine->amount);
            $invoiceLine->setCurrency($originalLine->currency);
            $invoiceLine->setMainSubscriptionReference($originalLine->subscription);
            $invoiceLine->setChildSubscriptionReference($originalLine->subscription_item);

            $output->addInvoiceLine($invoiceLine);
        }

        return $output;
    }
}
