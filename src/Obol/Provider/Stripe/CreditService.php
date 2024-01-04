<?php

declare(strict_types=1);

/*
 * Copyright Humbly Arrogant Software Limited 2020-2024
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: 26.06.2026 ( 3 years after 2.2.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace Obol\Provider\Stripe;

use Obol\CreditServiceInterface;
use Obol\Model\Credit\BalanceOutput;
use Obol\Model\Credit\CreditTransaction;
use Obol\Provider\ProviderInterface;
use Stripe\CustomerBalanceTransaction;
use Stripe\StripeClient;

class CreditService implements CreditServiceInterface
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

    public function addCreditTransaction(CreditTransaction $creditTransaction): BalanceOutput
    {
        $multiplier = ('debit' === $creditTransaction->getType()) ? -1 : 1;
        $amount = $creditTransaction->getAmount() * $multiplier;
        $currency = strtolower($creditTransaction->getCurrency());

        $stripeCredit = $this->stripe->customers->createBalanceTransaction($creditTransaction->getCustomerReference(), ['amount' => $amount, 'currency' => $currency]);

        $balanceOutput = $this->getBalanceOutput($stripeCredit);

        return $balanceOutput;
    }

    public function getAllForCustomer(string $customerId, int $limit = 10, ?string $lastId = null): array
    {
        $payload = ['limit' => $limit];
        if (isset($lastId) && !empty($lastId)) {
            $payload['starting_after'] = $lastId;
        }
        $stripeCredits = $this->stripe->customers->allBalanceTransactions($customerId, $payload);

        $output = [];
        /** @var CustomerBalanceTransaction $stripeCredit */
        foreach ($stripeCredits->data as $stripeCredit) {
            $output[] = $this->getBalanceOutput($stripeCredit);
        }

        return $output;
    }

    public function getBalanceOutput(CustomerBalanceTransaction $stripeCredit): BalanceOutput
    {
        $createdAt = new \DateTime();
        $createdAt->setTimestamp($stripeCredit->created);
        $balanceOutput = new BalanceOutput();
        $balanceOutput->setId($stripeCredit->id);
        $balanceOutput->setAmount($stripeCredit->amount);
        $balanceOutput->setCurrency($stripeCredit->currency);
        $balanceOutput->setCustomerReference($stripeCredit->customer);
        $balanceOutput->setCreatedAt($createdAt);
        $balanceOutput->setDescription($stripeCredit->description);

        return $balanceOutput;
    }
}
