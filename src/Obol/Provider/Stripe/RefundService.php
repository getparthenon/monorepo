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

use Obol\Exception\ProviderFailureException;
use Obol\Model\Refund;
use Obol\Model\Refund\IssueRefund;
use Obol\Provider\ProviderInterface;
use Obol\RefundServiceInterface;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class RefundService implements RefundServiceInterface
{
    protected StripeClient $stripe;

    protected Config $config;

    protected ProviderInterface $provider;

    public function __construct(ProviderInterface $provider, Config $config, ?StripeClient $stripe = null)
    {
        $this->provider = $provider;
        $this->config = $config;
        $this->stripe = $stripe ?? new StripeClient($this->config->getApiKey());
    }

    public function issueRefund(IssueRefund $issueRefund): Refund
    {
        try {
            $stripeRefund = $this->stripe->refunds->create(['amount' => $issueRefund->getAmount()->getMinorAmount()->toInt(), 'charge' => $issueRefund->getPaymentExternalReference()]);
            $refund = new Refund();
            $refund->setId($stripeRefund->id);
            $refund->setAmount($stripeRefund->amount);
            $refund->setCurrency($stripeRefund->currency);
            $refund->setPaymentId($issueRefund->getPaymentExternalReference());

            return $refund;
        } catch (ApiErrorException $exception) {
            throw new ProviderFailureException($exception->getMessage(), previous: $exception);
        }
    }

    public function list(int $limit = 10, ?string $lastId = null): array
    {
        $payload = ['limit' => $limit];
        if (isset($lastId) && !empty($lastId)) {
            $payload['starting_after'] = $lastId;
        }

        $result = $this->stripe->refunds->all($payload);
        $output = [];
        foreach ($result->data as $stripeRefund) {
            $refund = new Refund();
            $refund->setId($stripeRefund->id);
            $refund->setAmount($stripeRefund->amount);
            $refund->setCurrency($stripeRefund->currency);
            $refund->setPaymentId($stripeRefund->charge);
            $createdAt = new \DateTime();
            $createdAt->setTimestamp($stripeRefund->created);
            $refund->setCreatedAt($createdAt);
            $output[] = $refund;
        }

        return $output;
    }
}
