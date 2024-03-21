<?php

declare(strict_types=1);

/*
 * Copyright (C) 2020-2024 Iain Cambridge
 *
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Obol;

use Obol\Exception\ProviderFailureException;
use Obol\Model\Product;
use Obol\Model\ProductCreation;

interface ProductServiceInterface
{
    /**
     * @throws ProviderFailureException
     */
    public function createProduct(Product $product): ProductCreation;

    public function fetchProduct(string $productId): Product;

    /**
     * @return Product[]
     */
    public function list(int $limit = 10, ?string $lastId = null): array;
}
