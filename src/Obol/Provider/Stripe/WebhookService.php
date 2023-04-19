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

use Obol\Model\Events\DisputeClosed;
use Obol\Model\Events\DisputeCreation;
use Obol\Model\WebhookPayload;
use Obol\Provider\ProviderInterface;
use Obol\WebhookServiceInterface;
use Stripe\Dispute;
use Stripe\StripeClient;

class WebhookService implements WebhookServiceInterface
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

    public function process(WebhookPayload $payload): mixed
    {
        $event = \Stripe\Webhook::constructEvent($payload->getPayload(), $payload->getSignature(), $payload->getSignature());
        switch ($event->type) {
            case 'charge.dispute.created':
                return $this->processDisputeCreated($event->object);
            case 'charge.dispute.closed':
                return $this->processDisputeClosed($event->object);
            default:
                return null;
        }
    }

    private function processDisputeCreated(Dispute $dispute): DisputeCreation
    {
        $datetime = new \DateTime();
        $datetime->setTimestamp($dispute->created);

        $event = new DisputeCreation();
        $event->setDisputedPaymentId($dispute->charge);
        $event->setReason($dispute->reason);
        $event->setAmount($dispute->amount);
        $event->setCurrency($dispute->currency);
        $event->setCreatedAt($datetime);

        return $event;
    }

    private function processDisputeClosed(Dispute $dispute): DisputeClosed
    {
        $event = new DisputeClosed();
        $event->setDisputedPaymentId($dispute->charge);
        $event->setStatus($dispute->status);

        return $event;
    }
}
