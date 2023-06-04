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

use Obol\Model\Voucher\Voucher;
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
            'duration_in_months' => $voucher->getDurationInMonths(),
        ];

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
}
