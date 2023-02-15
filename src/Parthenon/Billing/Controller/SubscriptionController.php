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

namespace Parthenon\Billing\Controller;

use Obol\Model\Subscription;
use Obol\Provider\ProviderInterface;
use Parthenon\Billing\CustomerProviderInterface;
use Parthenon\Billing\Dto\StartSubscriptionDto;
use Parthenon\Billing\Obol\BillingDetailsFactoryInterface;
use Parthenon\Billing\Plan\PlanManagerInterface;
use Parthenon\Billing\Repository\PaymentDetailsRepositoryInterface;
use Parthenon\Common\Exception\NoEntityFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Transport\Serialization\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class SubscriptionController
{
    public function startSubscriptionWithPaymentDetails(
        Request $request,
        CustomerProviderInterface $customerProvider,
        PaymentDetailsRepositoryInterface $paymentDetailsRepository,
        BillingDetailsFactoryInterface $billingDetailsFactory,
        PlanManagerInterface $planManager,
        SerializerInterface $serializer,
        ProviderInterface $provider,
    ) {
        $customer = $customerProvider->getCurrentCustomer();

        /** @var StartSubscriptionDto $subscriptionDto */
        $subscriptionDto = $serializer->deserialize($request->getContent(), StartSubscriptionDto::class, 'json');

        try {
            $paymentDetails = $paymentDetailsRepository->getDefaultPaymentDetailsForCustomer($customer);
            $billingDetails = $billingDetailsFactory->createFromCustomerAndPaymentDetails($customer, $paymentDetails);

            $plan = $planManager->getPlanByName($subscriptionDto->getPlanName());

            $subscription = new Subscription();
            $subscription->setBillingDetails($billingDetails);
            $subscription->setSeats($subscriptionDto->getSeatNumbers());
            // TODO add price to $plan

            $provider->payments()->startSubscription($subscription);
        } catch (NoEntityFoundException $exception) {
            return new JsonResponse(['success' => false], JsonResponse::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['success' => true], JsonResponse::HTTP_BAD_REQUEST);
    }
}
