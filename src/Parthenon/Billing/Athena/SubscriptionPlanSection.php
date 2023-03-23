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
use Parthenon\Billing\Entity\SubscriptionPlan;
use Parthenon\Billing\Form\Type\SubscriptionPlanLimitType;
use Parthenon\Billing\Repository\SubscriptionLimitRepositoryInterface;
use Parthenon\Billing\Repository\SubscriptionPlanRepositoryInterface;

class SubscriptionPlanSection extends AbstractSection
{
    public function __construct(
        private SubscriptionPlanRepositoryInterface $subscriptionPlanRepository,
        private SubscriptionLimitRepositoryInterface $subscriptionLimitRepository,
    ) {
    }

    public function getUrlTag(): string
    {
        return 'billing-subscription-plan';
    }

    public function getRepository(): CrudRepositoryInterface
    {
        return $this->subscriptionPlanRepository;
    }

    public function getEntity()
    {
        return new SubscriptionPlan();
    }

    public function getMenuSection(): string
    {
        return 'Billing';
    }

    public function getMenuName(): string
    {
        return 'Subscription Plan';
    }

    public function buildEntityForm(EntityForm $entityForm): EntityForm
    {
        $choices = $this->subscriptionLimitRepository->getAll();

        $entityForm->section('Main')
                ->field('name', 'text')
                ->field('public', 'checkbox')
                ->field('external_reference', 'text', ['required' => false])
                ->field('is_free', 'checkbox')
                ->field('is_per_seat', 'checkbox')
                ->field('user_count', 'text')
            ->end()
            ->section('Limits')
                ->field('limits', 'collection',
                    [
                        'entry_type' => SubscriptionPlanLimitType::class,
                        'entry_options' => [
                            'label' => false,
                            'choices' => $choices,
                        ],
                        'allow_add' => true,
                        'allow_delete' => true,
                        'by_reference' => false,
                        'delete_empty' => true,
                    ]
                )
            ->end();

        return $entityForm;
    }

    public function buildListView(ListView $listView): ListView
    {
        $listView->addField('name', 'text');

        return $listView;
    }
}