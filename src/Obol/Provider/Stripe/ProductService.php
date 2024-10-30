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

namespace Obol\Provider\Stripe;

use Obol\Exception\ProviderFailureException;
use Obol\Model\Product;
use Obol\Model\ProductCreation;
use Obol\ProductServiceInterface;
use Obol\Provider\ProviderInterface;
use Psr\Log\LoggerAwareTrait;
use Stripe\StripeClient;

class ProductService implements ProductServiceInterface
{
    use LoggerAwareTrait;

    protected StripeClient $stripe;

    protected Config $config;

    protected ProviderInterface $provider;

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
        if (true === $productResponse->livemode) {
            $url = sprintf('https://dashboard.stripe.com/products/%s', $productResponse->id);
        } else {
            $url = sprintf('https://dashboard.stripe.com/test/products/%s', $productResponse->id);
        }

        $productCreation = new ProductCreation();
        $productCreation->setReference($productResponse->id);
        $productCreation->setDetailsUrl($url);

        return $productCreation;
    }

    public function fetchProduct(string $productId): Product
    {
        $stripeProduct = $this->stripe->products->retrieve($productId);

        $product = $this->populateProduct($stripeProduct);

        return $product;
    }

    public function list(int $limit = 10, ?string $lastId = null): array
    {
        $payload = ['limit' => $limit];
        if (isset($lastId) && !empty($lastId)) {
            $payload['starting_after'] = $lastId;
        }
        $result = $this->stripe->products->all($payload);
        $output = [];

        foreach ($result->data as $stripeProduct) {
            $output[] = $this->populateProduct($stripeProduct);
        }

        return $output;
    }

    public function populateProduct(\Stripe\Product $stripeProduct): Product
    {
        if (true === $stripeProduct->livemode) {
            $url = sprintf('https://dashboard.stripe.com/products/%s', $stripeProduct->id);
        } else {
            $url = sprintf('https://dashboard.stripe.com/test/products/%s', $stripeProduct->id);
        }

        $product = new Product();
        $product->setId($stripeProduct->id);
        $product->setName($stripeProduct->name);
        $product->setUrl($url);

        return $product;
    }
}
