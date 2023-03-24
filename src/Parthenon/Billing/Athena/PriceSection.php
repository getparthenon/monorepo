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

namespace Parthenon\Billing\Athena;

use Parthenon\Athena\AbstractSection;
use Parthenon\Athena\EntityForm;
use Parthenon\Athena\ListView;
use Parthenon\Athena\Repository\CrudRepositoryInterface;
use Parthenon\Billing\Entity\Price;
use Parthenon\Billing\Obol\PriceRegisterInterface;
use Parthenon\Billing\Repository\PriceRepositoryInterface;
use Parthenon\Billing\Repository\ProductRepositoryInterface;

class PriceSection extends AbstractSection
{
    public function __construct(
        private PriceRepositoryInterface $priceRepository,
        private PriceRegisterInterface $priceRegister,
        private ProductRepositoryInterface $productRepository,
    ) {
    }

    public function getUrlTag(): string
    {
        return 'billing-price';
    }

    public function getRepository(): CrudRepositoryInterface
    {
        return $this->priceRepository;
    }

    public function getEntity()
    {
        return new Price();
    }

    public function getMenuSection(): string
    {
        return 'Billing';
    }

    public function getMenuName(): string
    {
        return 'Prices';
    }

    /**
     * @param Price $entity
     */
    public function preSave($entity): void
    {
        if (!$entity->hasExternalReference()) {
            $this->priceRegister->registerPrice($entity);
        }
    }

    public function buildListView(ListView $listView): ListView
    {
        $listView->addField('amount', 'text')
            ->addField('currency', 'text')
            ->addField('schedule', 'text');

        return $listView;
    }

    public function buildEntityForm(EntityForm $entityForm): EntityForm
    {
        $products = $this->productRepository->getAll();

        $entityForm->section('Main')
                ->field('amount')
                ->field('currency', 'choice', ['choices' => ['Euro' => 'EUR', 'British Pounds' => 'GBP', 'US Dollars' => 'USD', 'AU Dollars' => 'AUD']])
                ->field('recurring', 'checkbox', ['required' => false])
                ->field('schedule', 'choice', ['choices' => ['Yearly' => 'year', 'Monthly' => 'month', 'Weekly' => 'week']])
                ->field('includingTax', 'checkbox', ['required' => false])
                ->field('product', 'choice', ['choices' => $products, 'choice_label' => 'name', 'choice_value' => 'id'])
            ->end();

        return $entityForm;
    }
}