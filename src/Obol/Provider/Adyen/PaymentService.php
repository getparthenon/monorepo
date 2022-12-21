<?php

declare(strict_types=1);

/*
 * Copyright Iain Cambridge 2020-2022.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: TBD ( 3 years after 2.2.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace Obol\Provider\Adyen;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Obol\Model\BillingDetails;
use Obol\Model\CardFileDeletedResponse;
use Obol\Model\CardOnFileResponse;
use Obol\Model\Charge;
use Obol\Model\ChargeCardResponse;
use Obol\Model\Subscription;
use Obol\Model\SubscriptionCreationResponse;
use Obol\Model\SubscriptionStoppedResponse;
use Obol\PaymentServiceInterface;
use Obol\Provider\Adyen\DataMapper\PaymentDetailsMapper;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

class PaymentService implements PaymentServiceInterface
{
    private const TEST_BASE_URL = 'https://kyc-test.adyen.com/lem/v2/legalEntities';
    private const LIVE_BASE_URL = 'https://kyc-live.adyen.com/lem/v2/legalEntities';
    private ClientInterface $client;
    private RequestFactoryInterface $requestFactory;
    private StreamFactoryInterface $streamFactory;
    private PaymentDetailsMapper $paymentDetailsMapper;
    private string $baseUrl;

    public function __construct(
        private Config $config,
        ?PaymentDetailsMapper $paymentDetailsMapper = null,
        ?ClientInterface $client = null,
        ?RequestFactoryInterface $requestFactory = null,
        ?StreamFactoryInterface $streamFactory = null,
    ) {
        $this->paymentDetailsMapper = $paymentDetailsMapper ?? new PaymentDetailsMapper();
        $this->client = $client ?? Psr18ClientDiscovery::find();
        $this->requestFactory = $requestFactory ?? Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = $streamFactory ?? Psr17FactoryDiscovery::findStreamFactory();
        $this->baseUrl = $this->config->isTestMode() ? self::TEST_BASE_URL : self::LIVE_BASE_URL;
    }

    public function startSubscription(Subscription $subscription): SubscriptionCreationResponse
    {
        if (!$subscription->getBillingDetails()->hasCustomerReference()) {
            $this->setCustomerReference($subscription->getBillingDetails());
        }

        // TODO: Implement startSubscription() method.
    }

    public function stopSubscription(): SubscriptionStoppedResponse
    {
        // TODO: Implement stopSubscription() method.
    }

    public function createCardOnFile(BillingDetails $billingDetails): CardOnFileResponse
    {
        if (!$billingDetails->hasCustomerReference()) {
            $this->setCustomerReference($billingDetails);
        }
    }

    public function deleteCardFile(BillingDetails $cardFile): CardFileDeletedResponse
    {
        if (!$cardFile->usePrestoredCard()) {
            throw new \Exception('No card data to delete');
        }

        // TODO: Implement deleteCardFile() method.
    }

    public function chargeCardOnFile(Charge $charge): ChargeCardResponse
    {
        // TODO: Implement chargeCardOnFile() method.
    }

    protected function setCustomerReference(BillingDetails $billingDetails): void
    {
        if ($billingDetails->hasCustomerReference()) {
            return;
        }

        $bytes = random_bytes(16);
        $customerReference = bin2hex($bytes);
        $billingDetails->setCustomerReference($customerReference);
    }
}
