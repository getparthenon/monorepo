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

namespace App\Tests\Behat\Qa\Billing;

use App\Repository\Orm\TeamRepository;
use App\Tests\Behat\Skeleton\TeamTrait;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Session;
use Brick\Money\Currency;
use Brick\Money\Money;
use Parthenon\Billing\Entity\Payment;
use Parthenon\Billing\Enum\PaymentStatus;
use Parthenon\Billing\Repository\Orm\PaymentServiceRepository;

class PaymentsContext implements Context
{
    use TeamTrait;

    public function __construct(
        private Session $session,
        private TeamRepository $teamRepository,
        private PaymentServiceRepository $paymentRepository
    ) {
    }

    /**
     * @Given the following payments exist:
     */
    public function theFollowingPaymentsExist(TableNode $table)
    {
        foreach ($table->getColumnsHash() as $row) {
            $money = Money::of($row['Amount'], Currency::of($row['Currency']));
            $team = $this->getTeamByName($row['Customer']);

            $payment = new Payment();
            $payment->setProvider($row['Provider']);
            $payment->setMoneyAmount($money);
            $payment->setCustomer($team);
            $payment->setPaymentReference(bin2hex(random_bytes(32)));
            $payment->setCreatedAt(new \DateTime('now'));
            $payment->setUpdatedAt(new \DateTime('now'));
            $payment->setCompleted('false' !== $row['Completed']);
            $payment->setChargedBack('false' !== $row['Charged Back']);
            $payment->setRefunded('false' !== $row['Refunded']);
            $payment->setStatus(PaymentStatus::COMPLETED);
            $this->paymentRepository->getEntityManager()->persist($payment);
        }
        $this->paymentRepository->getEntityManager()->flush();
    }

    /**
     * @When I go to payment athena page
     */
    public function iGoToPaymentAthenaPage()
    {
        $this->session->visit('/athena/billing-payments/list');
    }
}
