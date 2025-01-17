<?php

declare(strict_types=1);

/*
 * Copyright (C) 2020-2025 Iain Cambridge
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

namespace Parthenon\Billing\Controller;

use Obol\Exception\UnsupportedFunctionalityException;
use Parthenon\Billing\CustomerProviderInterface;
use Parthenon\Billing\Dto\StartSubscriptionDto;
use Parthenon\Billing\Enum\BillingChangeTiming;
use Parthenon\Billing\Exception\NoCustomerException;
use Parthenon\Billing\Exception\NoPaymentDetailsException;
use Parthenon\Billing\Exception\NoPlanFoundException;
use Parthenon\Billing\Exception\NoPlanPriceFoundException;
use Parthenon\Billing\Exception\PaymentFailureException;
use Parthenon\Billing\Plan\PlanManagerInterface;
use Parthenon\Billing\Repository\CustomerRepositoryInterface;
use Parthenon\Billing\Response\StartSubscriptionResponse;
use Parthenon\Billing\Subscription\SubscriptionManagerInterface;
use Parthenon\Billing\Subscription\SubscriptionProviderInterface;
use Parthenon\Common\Exception\NoEntityFoundException;
use Parthenon\Common\LoggerAwareTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SubscriptionController
{
    use LoggerAwareTrait;

    #[Route('/billing/subscription/{subscriptionId}/cancel', name: 'parthenon_billing_subscription_cancel', methods: ['POST'])]
    public function cancelSubscription(
        Request $request,
        SubscriptionProviderInterface $subscriptionProvider,
        SubscriptionManagerInterface $subscriptionManager,
    ): Response {
        try {
            $subscription = $subscriptionProvider->getSubscription($request->get('subscriptionId'));
        } catch (NoEntityFoundException $E) {
            return new JsonResponse(status: JsonResponse::HTTP_NOT_FOUND);
        }

        $subscriptionManager->cancelSubscriptionAtEndOfCurrentPeriod($subscription);

        return new JsonResponse(status: JsonResponse::HTTP_ACCEPTED);
    }

    #[Route('/billing/subscription/start', name: 'parthenon_billing_subscription_start_with_payment_details', methods: ['POST'])]
    public function startSubscriptionWithPaymentDetails(
        Request $request,
        CustomerProviderInterface $customerProvider,
        SerializerInterface $serializer,
        CustomerRepositoryInterface $customerRepository,
        ValidatorInterface $validator,
        SubscriptionManagerInterface $subscriptionManager,
    ): Response {
        $this->getLogger()->info('Starting the subscription');

        try {
            $customer = $customerProvider->getCurrentCustomer();
        } catch (NoCustomerException $exception) {
            $this->getLogger()->error('No customer found when starting subscription with payment details - probable misconfigured firewall.');

            return new JsonResponse(StartSubscriptionResponse::createGeneralError(), JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            /** @var StartSubscriptionDto $subscriptionDto */
            $subscriptionDto = $serializer->deserialize($request->getContent(), StartSubscriptionDto::class, 'json');

            $errors = $validator->validate($subscriptionDto);

            if (count($errors) > 0) {
                return new JsonResponse(StartSubscriptionResponse::createInvalidRequestResponse($errors), JsonResponse::HTTP_BAD_REQUEST);
            }

            $subscription = $subscriptionManager->startSubscriptionWithDto($customer, $subscriptionDto);

            $customerRepository->save($customer);
        } catch (NoEntityFoundException $exception) {
            return new JsonResponse(StartSubscriptionResponse::createGeneralError(), JsonResponse::HTTP_BAD_REQUEST);
        } catch (NoPlanPriceFoundException $exception) {
            $this->getLogger()->warning('No price plan found');

            return new JsonResponse(StartSubscriptionResponse::createPlanPriceNotFound(), JsonResponse::HTTP_BAD_REQUEST);
        } catch (NoPlanFoundException $exception) {
            $this->getLogger()->warning('No plan found');

            return new JsonResponse(StartSubscriptionResponse::createPlanNotFound(), JsonResponse::HTTP_BAD_REQUEST);
        } catch (PaymentFailureException $exception) {
            $this->getLogger()->warning('Payment failed so subscription was not created');

            return new JsonResponse(StartSubscriptionResponse::createPaymentFailed($exception->getChargeFailureReason()), JsonResponse::HTTP_BAD_REQUEST);
        } catch (NoPaymentDetailsException $exception) {
            $this->getLogger()->warning('Customer does not have payment details so subscription was not created');

            return new JsonResponse(StartSubscriptionResponse::createNoPaymentDetails(), JsonResponse::HTTP_BAD_REQUEST);
        } catch (UnsupportedFunctionalityException $exception) {
            $this->getLogger()->error('Payment provider does not support payment details');

            return new JsonResponse(StartSubscriptionResponse::createUnsupportedPaymentProvider(), JsonResponse::HTTP_BAD_REQUEST);
        } catch (\Throwable $t) {
            $this->getLogger()->error('Unknown error while starting a subscription');

            throw $t;
        }

        return new JsonResponse(StartSubscriptionResponse::createSuccessResponse($subscription), JsonResponse::HTTP_CREATED);
    }

    #[Route('/billing/subscription/{id}/change/{planName}/{schedule}/{currency}', name: 'parthenon_billing_subscription_change', methods: ['POST'])]
    public function changeSubscription(
        Request $request,
        SubscriptionProviderInterface $subscriptionProvider,
        SubscriptionManagerInterface $subscriptionManager,
        PlanManagerInterface $planManager,
    ): Response {
        $subscriptions = $subscriptionProvider->getSubscription($request->get('id'));

        $schedule = $request->get('schedule');
        $currency = $request->get('currency');
        $plan = $planManager->getPlanByName($request->get('planName'));
        $price = $plan->getPriceForPaymentSchedule($schedule, $currency);

        $subscriptionManager->changeSubscriptionPlan($subscriptions, $plan, $price, BillingChangeTiming::INSTANTLY);

        return new JsonResponse(['success' => true], JsonResponse::HTTP_CREATED);
    }
}
