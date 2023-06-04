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

use Obol\Model\Voucher\Amount;
use Obol\Model\Voucher\Voucher;
use Obol\Model\Voucher\VoucherApplicationResponse;
use Obol\Model\Voucher\VoucherCreation;
use Obol\Provider\ProviderInterface;
use Obol\VoucherServiceInterface;
use Stripe\StripeClient;

class VoucherService implements VoucherServiceInterface
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

    public function createVoucher(Voucher $voucher): VoucherCreation
    {
        $couponPayload = [
            'name' => $voucher->getName(),
            'duration' => $voucher->getDuration(),
        ];

        if ('repeating' === $voucher->getDuration()) {
            $couponPayload['duration_in_months'] = $voucher->getDurationInMonths();
        }

        if ('percentage' === $voucher->getType()) {
            $couponPayload['percent_off'] = $voucher->getPercentage();
        } else {
            $couponPayload['currency_options'] = [];
            $amounts = $voucher->getAmounts();
            $amount = array_shift($amounts);
            $couponPayload['amount_off'] = $amount->getAmount();
            $couponPayload['currency'] = $amount->getCurrency();

            foreach ($amounts as $amount) {
                $couponPayload['currency_options'][$amount->getCurrency()] = ['amount_off' => $amount->getAmount()];
            }
        }

        $stripeCoupon = $this->stripe->coupons->create($couponPayload);

        $response = new VoucherCreation();
        $response->setId($stripeCoupon->id);
        if ($voucher->getCode()) {
            $promoCodePayload = [
                'coupon' => $stripeCoupon->id,
                'code' => $voucher->getCode(),
            ];

            $stripePromo = $this->stripe->promotionCodes->create($promoCodePayload);
            $response->setPromoId($stripePromo->id);
        }

        return $response;
    }

    public function list(int $limit = 10, ?string $lastId = null): array
    {
        $payload = ['limit' => $limit];
        if (isset($lastId) && !empty($lastId)) {
            $payload['starting_after'] = $lastId;
        }

        $result = $this->stripe->coupons->all($payload);
        $output = [];
        foreach ($result->data as $coupon) {
            $type = null === $coupon->percent_off ? 'fixed_credit' : 'percentage';

            $voucher = new Voucher();
            $voucher->setId($coupon->id);
            $voucher->setType($type);
            $voucher->setDuration($coupon->duration);
            $voucher->setDurationInMonths($coupon->duration_in_months);
            if ('percentage' === $type) {
                $voucher->setPercentage($coupon->percent_off);
            } else {
                $amounts = [];
                $amount = new Amount();
                $amount->setAmount($coupon->amount_off);
                $amount->setCurrency($coupon->currency);
                $amounts[] = $amount;

                $voucher->setAmounts($amounts);
            }

            $createdAt = new \DateTime();
            $createdAt->setTimestamp($coupon->created);
            $voucher->setCreatedAt($createdAt);
            $output[] = $voucher;
        }

        return $output;
    }

    public function applyCoupon(string $customerReference, string $couponReference): VoucherApplicationResponse
    {
        $stripeCustomer = $this->stripe->customers->retrieve($customerReference);
        $stripeCustomer->coupon = $couponReference;
        $stripeCustomer->save();

        $response = new VoucherApplicationResponse();
        $response->setSuccess(true);

        return $response;
    }
}
