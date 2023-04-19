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
use App\Repository\Orm\UserRepository;
use App\Tests\Behat\Skeleton\SendRequestTrait;
use App\Tests\Behat\Skeleton\TeamTrait;
use App\Tests\Behat\Skeleton\UserTrait;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Session;
use Parthenon\Billing\Entity\PaymentMethod;
use Parthenon\Billing\Repository\Orm\PaymentMethodServiceRepository;

class MainContext implements Context
{
    use SendRequestTrait;
    use UserTrait;
    use TeamTrait;

    public function __construct(
        private Session $session,
        private UserRepository $userRepository,
        private TeamRepository $teamRepository,
        private PaymentMethodServiceRepository $paymentDetailsServiceRepository,
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

            $paymentDetails = new PaymentMethod();
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
        $this->sendJsonRequest('GET', '/api/billing/payment-details');
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

        $this->sendJsonRequest('DELETE', '/api/billing/payment-details/'.(string) $paymentDetails->getId());
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

        $this->sendJsonRequest('POST', '/api/billing/payment-details/'.(string) $paymentDetails->getId().'/default');
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

    protected function getPaymentDetailsFromLastFour(string $lastFour): PaymentMethod
    {
        $paymentDetails = $this->paymentDetailsServiceRepository->findOneBy(['lastFour' => $lastFour]);

        $this->paymentDetailsServiceRepository->getEntityManager()->refresh($paymentDetails);

        return $paymentDetails;
    }
}
