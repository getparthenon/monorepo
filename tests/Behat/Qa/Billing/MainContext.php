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
use App\Repository\Orm\UserRepository;
use App\Tests\Behat\Skeleton\SendRequestTrait;
use App\Tests\Behat\Skeleton\TeamTrait;
use App\Tests\Behat\Skeleton\UserTrait;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Session;
use Parthenon\Billing\Entity\PaymentCard;
use Parthenon\Billing\Repository\Orm\PaymentCardServiceRepository;

class MainContext implements Context
{
    use SendRequestTrait;
    use UserTrait;
    use TeamTrait;

    public function __construct(
        private Session $session,
        private UserRepository $userRepository,
        private TeamRepository $teamRepository,
        private PaymentCardServiceRepository $paymentDetailsServiceRepository,
    ) {
    }

    /**
     * @When I set my billing address as:
     */
    public function iSetMyBillingAddressAs(TableNode $table)
    {
        $sendData = [];

        foreach ($table->getRowsHash() as $key => $value) {
            $key = strtolower($key);
            $key = implode('_', explode(' ', $key));
            $sendData[$key] = $value;
        }

        $this->sendJsonRequest('POST', '/api/billing/details', $sendData);
    }

    /**
     * @Then the customer for :arg1 billing address should have a street line one as :arg2
     */
    public function theCustomerForBillingAddressShouldHaveAStreetLineOneAs($teamName, $streetLine)
    {
        $team = $this->getTeamByName($teamName);

        if ($team->getBillingAddress()->getStreetLineOne() !== $streetLine) {
            throw new \Exception("Street line doesn't match");
        }
    }

    /**
     * @Given the following payment methods exist:
     */
    public function theFollowingPaymentMethodsExist(TableNode $table)
    {
        foreach ($table->getColumnsHash() as $row) {
            $team = $this->getTeamByName($row['Team']);

            if (isset($row['Default'])) {
                $default = ('true' === strtolower($row['Default']));
            } else {
                $default = false;
            }

            $paymentDetails = new PaymentCard();
            $paymentDetails->setCustomer($team);
            $paymentDetails->setBrand($row['Brand']);
            $paymentDetails->setLastFour($row['Last Four']);
            $paymentDetails->setExpiryMonth($row['Expiry Month']);
            $paymentDetails->setExpiryYear($row['Expiry Year']);
            $paymentDetails->setStoredCustomerReference($team->getExternalCustomerReference());
            $paymentDetails->setStoredPaymentReference(bin2hex(random_bytes(32)));
            $paymentDetails->setCreatedAt(new \DateTime());
            $paymentDetails->setDefaultPaymentOption($default);
            $paymentDetails->setDeleted(false);

            $this->paymentDetailsServiceRepository->getEntityManager()->persist($paymentDetails);
            $this->paymentDetailsServiceRepository->getEntityManager()->flush();
        }
    }

    /**
     * @When I fetch the payment methods
     */
    public function iFetchThePaymentMethods()
    {
        $this->sendJsonRequest('GET', '/api/billing/payment-method');
    }

    /**
     * @Then there will be :arg1 payment methods
     */
    public function thereWillBePaymentMethods($count)
    {
        $data = $this->getJsonContent();

        if (count($data['payment_details']) != $count) {
            throw new \Exception("Count doesn't match");
        }
    }

    /**
     * @Then there will be a payment method with the last four :arg1
     */
    public function thereWillBeAPaymentMethodWithTheLastFour($arg1)
    {
        $data = $this->getJsonContent();

        foreach ($data['payment_details'] as $paymentDetail) {
            if ($paymentDetail['last_four'] == $arg1) {
                return;
            }
        }
        throw new \Exception('Card not found');
    }

    /**
     * @When I delete the payment method with the last four :arg1
     */
    public function iDeleteThePaymentMethodWithTheLastFour($lastFour)
    {
        $paymentDetails = $this->getPaymentDetailsFromLastFour($lastFour);

        $this->sendJsonRequest('DELETE', '/api/billing/payment-method/'.(string) $paymentDetails->getId());
    }

    /**
     * @Then the payment method with the last four :arg1 will be marked as deleted
     */
    public function thePaymentMethodWithTheLastFourWillBeMarkedAsDeleted($lastFour)
    {
        $paymentDetails = $this->getPaymentDetailsFromLastFour($lastFour);

        if (!$paymentDetails->isDeleted()) {
            throw new \Exception('Payment method not deleted');
        }
    }

    /**
     * @Then I should not see the payment method for :arg1
     */
    public function iShouldNotSeeThePaymentMethodFor($lastFour)
    {
        $data = $this->getJsonContent();

        foreach ($data['payment_details'] as $paymentDetail) {
            if ($paymentDetail['last_four'] == $lastFour) {
                throw new \Exception('Card found');
            }
        }
    }

    /**
     * @When I mark the payment method with the last four :arg1 as default
     */
    public function iMarkThePaymentMethodWithTheLastFourAsDefault($lastFour)
    {
        $paymentDetails = $this->getPaymentDetailsFromLastFour($lastFour);

        $this->sendJsonRequest('POST', '/api/billing/payment-method/'.(string) $paymentDetails->getId().'/default');
    }

    /**
     * @Then the payment method with the last four :arg1 will be marked as default
     */
    public function thePaymentMethodWithTheLastFourWillBeMarkedAsDefault($lastFour)
    {
        $paymentDetails = $this->getPaymentDetailsFromLastFour($lastFour);

        if (!$paymentDetails->isDefaultPaymentOption()) {
            throw new \Exception('Is not default method');
        }
    }

    /**
     * @Then the payment method with the last four :arg1 will not be marked as default
     */
    public function thePaymentMethodWithTheLastFourWillNotBeMarkedAsDefault($lastFour)
    {
        $paymentDetails = $this->getPaymentDetailsFromLastFour($lastFour);

        if ($paymentDetails->isDefaultPaymentOption()) {
            throw new \Exception('Is default method');
        }
    }

    /**
     * @When I view the billing plans
     */
    public function iViewTheBillingPlans()
    {
        $this->sendJsonRequest('GET', '/api/billing/plans');
    }

    protected function getPaymentDetailsFromLastFour(string $lastFour): PaymentCard
    {
        $paymentDetails = $this->paymentDetailsServiceRepository->findOneBy(['lastFour' => $lastFour]);

        $this->paymentDetailsServiceRepository->getEntityManager()->refresh($paymentDetails);

        return $paymentDetails;
    }
}
