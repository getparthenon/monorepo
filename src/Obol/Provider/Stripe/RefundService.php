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

    /**
     * @param StripeClient $stripe
     */
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
