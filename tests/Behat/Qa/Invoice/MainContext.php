<?php

declare(strict_types=1);

/*
 * Copyright Iain Cambridge 2020-2022.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: 16.12.2025
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace App\Tests\Behat\Qa\Invoice;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Session;
use Brick\Money\Money;
use GuzzleHttp\Client;
use Parthenon\Common\Address;
use Parthenon\Invoice\CountryRules;
use Parthenon\Invoice\Invoice;
use Parthenon\Invoice\InvoiceBuilder;
use Parthenon\Invoice\Vat\BasicCountryTypeRule;

class MainContext implements Context
{
    private Session $session;

    private array $items;

    private Invoice $invoice;

    private ?string $vatNumber = null;

    private ?CountryRules $countryRules = null;

    private ?string $country = null;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @Given I have the following invoice items
     */
    public function iHaveTheFollowingInvoiceItems(TableNode $table)
    {
        $this->items = $table->getColumnsHash();
    }

    /**
     * @When I generate an invoice
     */
    public function iGenerateAnInvoice()
    {
        $invoiceBuilder = new InvoiceBuilder();

        foreach ($this->items as $item) {
            $options = [];
            $options['quantity'] = (isset($item['Quantity'])) ? (int) $item['Quantity'] : 1;
            $options['vat'] = (isset($item['VAT %'])) ? (float) $item['VAT %'] : 0.0;
            if (isset($item['Goods Type'])) {
                $options['type'] = $item['Goods Type'];
            }
            $invoiceBuilder->addItem($item['Title'], Money::of($item['Value'], 'EUR'), $options);
        }

        if ($this->vatNumber) {
            $invoiceBuilder->addVatNumber($this->vatNumber);
        }

        if ($this->country) {
            $address = new Address();
            $address->setCountry($this->country);
            $invoiceBuilder->setBillerAddress($address);
        }

        if (isset($this->countryRules)) {
            $invoiceBuilder->setCountryRules($this->countryRules);
        }

        $this->invoice = $invoiceBuilder->build();
    }

    /**
     * @Then the invoice should have the total of :arg1
     */
    public function theInvoiceShouldHaveTheTotalOf($expectedTotal)
    {
        $actualTotal = $this->invoice->getTotal();
        if ($expectedTotal != $actualTotal) {
            throw new \Exception('The total is '.$actualTotal.' and not '.$expectedTotal);
        }
    }

    /**
     * @Given I have the vat number :arg1
     */
    public function iHaveTheVatNumber($vatNumber)
    {
        $this->vatNumber = $vatNumber;
    }

    /**
     * @Then the invoice should have the vat of :arg1
     */
    public function theInvoiceShouldHaveTheVatOf($expectedTotal)
    {
        $actualTotal = $this->invoice->getVat();

        if ($expectedTotal != $actualTotal) {
            throw new \Exception('The total is '.$actualTotal.' and not '.$expectedTotal);
        }
    }

    /**
     * @Then the invoice should have the total without vat of :arg1
     */
    public function theInvoiceShouldHaveTheTotalWithoutVatOf($expectedTotal)
    {
        $actualTotal = $this->invoice->getSubTotal();

        if ($expectedTotal != $actualTotal) {
            throw new \Exception('The total is '.$actualTotal.' and not '.$expectedTotal);
        }
    }

    /**
     * @Given the follow tax rules exist:
     */
    public function theFollowTaxRulesExist(TableNode $table)
    {
        $countryRules = new CountryRules();
        foreach ($table->getColumnsHash() as $columnsHash) {
            $countryRules->addRule(new BasicCountryTypeRule($columnsHash['Country'], $columnsHash['Goods Type'], (float) $columnsHash['Percentage']));
        }
        $this->countryRules = $countryRules;
    }

    /**
     * @Given the invoice is for the country of :arg1
     */
    public function theInvoiceIsForTheCountryOf($country)
    {
        $this->country = $country;
    }

    /**
     * @Then the invoice row for :arg1 should have the vat of :arg2
     */
    public function theInvoiceRowForShouldHaveTheVatOf($name, $expectedTotal)
    {
        $found = false;
        foreach ($this->invoice->getItems() as $item) {
            if ($item->getName() === $name) {
                $found = true;
                $actualTotal = $item->getVat()->getAmount()->__toString();
                if ($actualTotal != $expectedTotal) {
                    throw new \Exception('The total is '.$actualTotal.' and not '.$expectedTotal);
                }
            }
        }

        if (!$found) {
            throw new \Exception("Didn't find the row ".$name);
        }
    }

    /**
     * @When I download an invoice
     */
    public function iDownloadAnInvoice()
    {
        $this->session->visit('/');
        $client = new Client(['base_uri' => $this->session->getCurrentUrl()]);

        $request = $client->get('/invoice/download');
        $this->response = $request;
    }

    /**
     * @Then I will receive a pdf file
     */
    public function iWillReceiveAPdfFile()
    {
    }
}
