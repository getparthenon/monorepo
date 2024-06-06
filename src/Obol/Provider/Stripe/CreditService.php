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

use Obol\CreditServiceInterface;
use Obol\Model\Credit\BalanceOutput;
use Obol\Model\Credit\CreditTransaction;
use Obol\Provider\ProviderInterface;
use Psr\Log\LoggerAwareTrait;
use Stripe\CustomerBalanceTransaction;
use Stripe\StripeClient;

class CreditService implements CreditServiceInterface
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
