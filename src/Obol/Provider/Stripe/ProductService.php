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
use Obol\Model\Product;
use Obol\Model\ProductCreation;
use Obol\ProductServiceInterface;
use Obol\Provider\ProviderInterface;
use Stripe\StripeClient;

class ProductService implements ProductServiceInterface
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

    public function createProduct(Product $product): ProductCreation
    {
        try {
            $productResponse = $this->stripe->products->create(['name' => $product->getName()]);
        } catch (\Throwable $exception) {
            throw new ProviderFailureException(previous: $exception);
        }

        $productCreation = new ProductCreation();
        $productCreation->setReference($productResponse->id);

        return $productCreation;
    }
}
