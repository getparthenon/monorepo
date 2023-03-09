<?php

namespace Parthenon\Billing\Athena;

use Parthenon\Athena\AbstractSection;
use Parthenon\Athena\ListView;
use Parthenon\Athena\Repository\CrudRepositoryInterface;
use Parthenon\Billing\Entity\Payment;
use Parthenon\Billing\Repository\PaymentRepositoryInterface;

class PaymentSection extends AbstractSection
{
    public function __construct(private PaymentRepositoryInterface $paymentRepository) {}

    public function getUrlTag(): string
    {
        return 'billing-payments';
    }

    public function getRepository(): CrudRepositoryInterface
    {
        return $this->paymentRepository;
    }

    public function getEntity()
    {
        return new Payment();
    }

    public function getMenuSection(): string
    {
        return 'Billing';
    }

    public function getMenuName(): string
    {
        return 'Payments';
    }

    public function buildListView(ListView $listView): ListView
    {
        $listView
            ->addField('customer.id', 'text')
            ->addField('provider', 'text')
            ->addField('amount', 'text');

        return $listView;
    }
}