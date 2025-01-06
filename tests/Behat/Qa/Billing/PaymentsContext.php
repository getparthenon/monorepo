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
        private PaymentServiceRepository $paymentRepository,
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
