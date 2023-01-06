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
use Parthenon\Billing\Entity\PaymentDetails;
use Parthenon\Billing\Repository\Orm\PaymentDetailsServiceRepository;

class MainContext implements Context
{
    use SendRequestTrait;
    use UserTrait;
    use TeamTrait;

    public function __construct(
        private Session $session,
        private UserRepository $userRepository,
        private TeamRepository $teamRepository,
        private PaymentDetailsServiceRepository $paymentDetailsServiceRepository,
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

            $paymentDetails = new PaymentDetails();
            $paymentDetails->setCustomer($team);
            $paymentDetails->setBrand($row['Brand']);
            $paymentDetails->setLastFour($row['Last Four']);
            $paymentDetails->setExpiryMonth($row['Expiry Month']);
            $paymentDetails->setExpiryYear($row['Expiry Year']);
            $paymentDetails->setStoredCustomerReference($team->getExternalCustomerReference());
            $paymentDetails->setStoredPaymentReference(bin2hex(random_bytes(32)));
            $paymentDetails->setCreatedAt(new \DateTime());
            $paymentDetails->setDefaultPaymentOption(false);
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
}
