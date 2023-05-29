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

use Obol\Model\Enum\ChargeFailureReasons;
use Obol\Model\Events\AbstractCharge;
use Obol\Model\Events\AbstractDispute;
use Obol\Model\Events\ChargeFailed;
use Obol\Model\Events\ChargeSucceeded;
use Obol\Model\Events\DisputeClosed;
use Obol\Model\Events\DisputeCreation;
use Obol\Model\Events\EventInterface;
use Obol\Model\Webhook\WebhookCreation;
use Obol\Model\WebhookPayload;
use Obol\Provider\ProviderInterface;
use Obol\WebhookServiceInterface;
use Stripe\Charge;
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

    public function registerWebhook(string $url, array $events, string $description = null): WebhookCreation
    {
        $stripeResult = $this->stripe->webhookEndpoints->create(['url' => $url, 'enabled_events' => $events, 'description' => $description]);

        $webhookCreation = new WebhookCreation();
        $webhookCreation->setId($stripeResult->id);
        $webhookCreation->setDescription($stripeResult->description);
        $webhookCreation->setEvents($stripeResult->enabled_events);

        return $webhookCreation;
    }

    public function deregisterWebhook(string $id): void
    {
        $this->stripe->webhookEndpoints->delete($id);
    }

    public function process(WebhookPayload $payload): ?EventInterface
    {
        $event = \Stripe\Webhook::constructEvent($payload->getPayload(), $payload->getSignature(), $payload->getSecret());
        switch ($event->type) {
            case 'charge.dispute.created':
                return $this->processDisputeCreated($event->data->object);
            case 'charge.dispute.closed':
                return $this->processDisputeClosed($event->data->object);
            case 'charge.succeeded':
                return $this->processChargeApproved($event->data->object);
            case 'charge.failed':
                return $this->processChargeFailed($event->data->object);
            default:
                return null;
        }
    }

    private function processChargeApproved(Charge $charge): ChargeSucceeded
    {
        $event = new ChargeSucceeded();

        $this->populateChargeEvent($charge, $event);

        return $event;
    }

    private function processChargeFailed(Charge $charge): ChargeFailed
    {
        $event = new ChargeFailed();

        $this->populateChargeEvent($charge, $event);

        $reason = match ($charge->failure_code) {
            'authentication_required' => ChargeFailureReasons::AUTHENTICATION_REQUIRED,
            'invalid_account', 'currency_not_supported', 'incorrect_number', 'incorrect_cvc', 'incorrect_pin', 'incorrect_zip', 'card_not_supported', 'invalid_amount', 'invalid_cvc', 'invalid_number', 'invalid_expiry_month', 'invalid_expiry_year' => ChargeFailureReasons::INVALID_DETAILS,
            'call_issuer', 'do_not_honor', 'do_not_try_again', 'new_account_information_available', 'no_action_taken', 'not_permitted' => ChargeFailureReasons::CONTACT_PROVIDER,
            'insufficient_funds' => ChargeFailureReasons::LACK_OF_FUNDS,
            default => ChargeFailureReasons::GENERAL_DECLINE,
        };

        $event->setReason($reason);

        return $event;
    }

    private function populateDisputeEvent(Dispute $dispute, AbstractDispute $event): void
    {
        $datetime = new \DateTime();
        $datetime->setTimestamp($dispute->created);
        $event->setPaymentReference($dispute->charge);
        $event->setReason($dispute->reason);
        $event->setAmount($dispute->amount);
        $event->setCurrency($dispute->currency);
        $event->setCreatedAt($datetime);
        $event->setStatus($dispute->status);
        $event->setId($dispute->id);
    }

    private function populateChargeEvent(Charge $charge, AbstractCharge $event): void
    {
        $datetime = new \DateTime();
        $datetime->setTimestamp($charge->created);
        $event->setAmount($charge->amount);
        $event->setCurrency($charge->currency);
        $event->setExternalCustomerId($charge->customer);
        $event->setExternalPaymentId($charge->id);
        $event->setExternalPaymentMethodId($charge->payment_method);
        $event->setId($charge->id);

        if (true === $charge->livemode) {
            $url = sprintf('https://dashboard.stripe.com/payments/%s', $charge->id);
        } else {
            $url = sprintf('https://dashboard.stripe.com/test/payments/%s', $charge->id);
        }
        $event->setDetailsLink($url);
    }

    private function processDisputeCreated(Dispute $dispute): DisputeCreation
    {
        $event = new DisputeCreation();
        $this->populateDisputeEvent($dispute, $event);

        return $event;
    }

    private function processDisputeClosed(Dispute $dispute): DisputeClosed
    {
        $event = new DisputeClosed();
        $this->populateDisputeEvent($dispute, $event);

        return $event;
    }
}
