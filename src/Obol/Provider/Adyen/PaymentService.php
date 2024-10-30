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

namespace Obol\Provider\Adyen;

use Brick\Money\Money;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Obol\Exception\UnsupportedFunctionalityException;
use Obol\Model\BillingDetails;
use Obol\Model\CancelSubscription;
use Obol\Model\CardOnFileResponse;
use Obol\Model\Charge;
use Obol\Model\ChargeCardResponse;
use Obol\Model\FrontendCardProcess;
use Obol\Model\Subscription;
use Obol\Model\SubscriptionCancellation;
use Obol\Model\SubscriptionCreationResponse;
use Obol\PaymentServiceInterface;
use Obol\Provider\Adyen\DataMapper\PaymentDetailsMapper;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;

class PaymentService implements PaymentServiceInterface
{
    use CustomerReferenceTrait;

    private const TEST_BASE_URL = 'https://checkout-test.adyen.com/v69/payments';
    private const LIVE_BASE_URL = 'https://%s-checkout-live.adyenpayments.com/checkout/v69/payments';

    private const TEST_DISABLE_URL = 'https://pal-test.adyen.com/pal/servlet/Recurring/v68/disable';
    private const LIVE_DISABLE_URL = 'https://%s-pal-live.adyenpayments.com/pal/servlet/Recurring/v68/disable';
    private ClientInterface $client;
    private RequestFactoryInterface $requestFactory;
    private StreamFactoryInterface $streamFactory;
    private PaymentDetailsMapper $paymentDetailsMapper;
    private string $baseUrl;
    private string $disableUrl;

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
        $this->baseUrl = $this->config->isTestMode() ? self::TEST_BASE_URL : sprintf(self::LIVE_BASE_URL, $this->config->getPrefix());
        $this->disableUrl = $this->config->isTestMode() ? self::TEST_DISABLE_URL : sprintf(self::LIVE_DISABLE_URL, $this->config->getPrefix());
    }

    public function startSubscription(Subscription $subscription): SubscriptionCreationResponse
    {
        if (!$subscription->getBillingDetails()->hasCustomerReference()) {
            $this->setCustomerReference($subscription->getBillingDetails());
        }

        $payload = $this->paymentDetailsMapper->subscriptionPayload($subscription, $this->config);

        $request = $this->createApiRequest('POST', $this->baseUrl);
        $request = $request->withBody($this->streamFactory->createStream(json_encode($payload)));

        $response = $this->client->sendRequest($request);

        $jsonData = json_decode($response->getBody()->getContents(), true);

        if (200 === $response->getStatusCode()) {
            $paymentDetails = $this->paymentDetailsMapper->buildPaymentDetails($jsonData);
            $paymentDetails->setAmount($subscription->getTotalCost());

            $subscriptionCreationResponse = new SubscriptionCreationResponse();
            $subscriptionCreationResponse->setPaymentDetails($paymentDetails)
                ->setSubscriptionId($jsonData['pspReference'])
            ->setLineId($jsonData['pspReference']);

            return $subscriptionCreationResponse;
        }

        if (401 === $response->getStatusCode()) {
            throw new \Exception('Unauthorized request - most likely an invalid API key');
        }

        if (403 === $response->getStatusCode()) {
            throw new \Exception('Forbidden - most likely invalid roles. Check if you have PCI rights');
        }

        throw new \Exception('Unable to make request');
    }

    public function stopSubscription(CancelSubscription $cancelSubscription): SubscriptionCancellation
    {
        $this->deleteCardFile($cancelSubscription->getSubscription()->getBillingDetails());

        $subscriptionCancellation = new SubscriptionCancellation();
        $subscriptionCancellation->setSubscription($cancelSubscription->getSubscription());

        return $subscriptionCancellation;
    }

    public function createCardOnFile(BillingDetails $billingDetails): CardOnFileResponse
    {
        if (!$billingDetails->hasCustomerReference()) {
            $this->setCustomerReference($billingDetails);
        }

        $payload = $this->paymentDetailsMapper->addCardToFilePayload($billingDetails, $this->config);

        $request = $this->createApiRequest('POST', $this->baseUrl);
        $request = $request->withBody($this->streamFactory->createStream(json_encode($payload)));

        $response = $this->client->sendRequest($request);

        $jsonData = json_decode($response->getBody()->getContents(), true);

        if (200 === $response->getStatusCode()) {
            $paymentDetails = $this->paymentDetailsMapper->buildPaymentDetails($jsonData);
            $paymentDetails->setAmount(Money::of(0, 'USD'));
            $cardOnFile = new CardOnFileResponse();
            $cardOnFile->setPaymentDetails($paymentDetails);

            return $cardOnFile;
        }

        if (401 === $response->getStatusCode()) {
            throw new \Exception('Unauthorized request - most likely an invalid API key');
        }

        if (403 === $response->getStatusCode()) {
            throw new \Exception('Forbidden - most likely invalid roles. Check if you have PCI rights');
        }

        throw new \Exception('Unable to make request');
    }

    public function deleteCardFile(BillingDetails $billingDetails): void
    {
        if (!$billingDetails->usePrestoredCard()) {
            throw new \Exception('No card data to delete');
        }

        $payload = $this->paymentDetailsMapper->addCardToFilePayload($billingDetails, $this->config);

        $request = $this->createApiRequest('POST', $this->disableUrl);
        $request = $request->withBody($this->streamFactory->createStream(json_encode($payload)));

        $response = $this->client->sendRequest($request);

        $jsonData = json_decode($response->getBody()->getContents(), true);

        if (200 === $response->getStatusCode()) {
            return;
        }

        if (401 === $response->getStatusCode()) {
            throw new \Exception('Unauthorized request - most likely an invalid API key');
        }

        if (403 === $response->getStatusCode()) {
            throw new \Exception('Forbidden - most likely invalid roles. Check if you have PCI rights');
        }

        throw new \Exception('Unable to make request');
    }

    public function chargeCardOnFile(Charge $charge): ChargeCardResponse
    {
        if (!$charge->getBillingDetails()->usePrestoredCard()) {
            throw new \Exception('No card data to delete');
        }

        $payload = $this->paymentDetailsMapper->chargeCardPayload($charge, $this->config);

        $request = $this->createApiRequest('POST', $this->baseUrl);
        $request = $request->withBody($this->streamFactory->createStream(json_encode($payload)));

        $response = $this->client->sendRequest($request);

        $jsonData = json_decode($response->getBody()->getContents(), true);

        if (200 === $response->getStatusCode()) {
            $paymentDetails = $this->paymentDetailsMapper->buildPaymentDetails($jsonData);
            $paymentDetails->setAmount($charge->getAmount());

            $cardOnFile = new ChargeCardResponse();
            $cardOnFile->setPaymentDetails($paymentDetails);

            return $cardOnFile;
        }

        throw new \Exception('Unable to make request');
    }

    public function startFrontendCreateCardOnFile(BillingDetails $billingDetails): FrontendCardProcess
    {
        throw new UnsupportedFunctionalityException();
    }

    public function makeCardDefault(BillingDetails $billingDetails): void
    {
        // TODO: Implement makeCardDefault() method.
    }

    public function list(int $limit = 10, ?string $lastId = null): array
    {
        // TODO: Implement list() method.
    }

    protected function createApiRequest(string $method, string $url): RequestInterface
    {
        $request = $this->requestFactory->createRequest($method, $url);

        return $request->withAddedHeader('x-API-key', $this->config->getApiKey())->withAddedHeader('Content-Type', 'application/json');
    }
}
