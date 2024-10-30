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

use Obol\InvoiceServiceInterface;
use Obol\Model\Invoice\Invoice;
use Obol\Model\Invoice\InvoiceLine;
use Obol\Provider\ProviderInterface;
use Psr\Log\LoggerAwareTrait;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class InvoiceService implements InvoiceServiceInterface
{
    use LoggerAwareTrait;

    protected StripeClient $stripe;

    protected Config $config;

    protected ProviderInterface $provider;

    public function __construct(ProviderInterface $provider, Config $config, ?StripeClient $stripe = null)
    {
        $this->provider = $provider;
        $this->config = $config;
        $this->stripe = $stripe ?? new StripeClient($this->config->getApiKey());
    }

    public function fetch(string $id): ?Invoice
    {
        try {
            $stripeInvoice = $this->stripe->invoices->retrieve($id);

            if (!$stripeInvoice) {
                return null;
            }
        } catch (ApiErrorException $exception) {
            return null;
        }

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
