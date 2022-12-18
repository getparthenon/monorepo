<?php

declare(strict_types=1);

/*
 * Copyright Iain Cambridge 2020-2022.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: TBD ( 3 years after 2.2.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace Tests\Obol\Provider\AuthorizeNet\DataMapper;

use Obol\Exception\ValidationFailureException;
use Obol\Models\Address;
use Obol\Models\Customer;
use Obol\Models\Enum\CustomerType;
use Obol\Provider\AuthorizeNet\DataMapper\CustomerMapper;
use PHPUnit\Framework\TestCase;

class CustomerMapperTest extends TestCase
{
    public const STREETLINEONE = '1 Example Lane';
    public const STREETLINETWO = 'Second Line';
    public const CITY = 'Example';
    public const COUNTRY = 'US';
    public const EMAIL = 'iain.cambridge@example.org';
    public const PHONE = '+44 1505 4033033';
    public const STATE = 'Example State';
    public const POSTALCODE = '10458';
    public const NAME = 'Iain Cambridge';

    public function testIndiviualMapping()
    {
        $address = new Address();
        $address->setStreetLineOne(self::STREETLINEONE)
            ->setStreetLineTwo(self::STREETLINETWO)
            ->setCity(self::CITY)
            ->setState(self::STATE)
            ->setCountryCode(self::COUNTRY)
            ->setPostalCode(self::POSTALCODE);

        $customer = new Customer();

        $customer
            ->setName(self::NAME)
            ->setEmail(self::EMAIL)
            ->setType(CustomerType::INDIVIDUAL)
            ->setPhone(self::PHONE)
            ->setAddress($address)
            ->setDescription('A test customer');

        $subject = new CustomerMapper();

        $result = $subject->mapCustomer($customer);

        $this->assertEquals(self::NAME, $result['name']);
        $this->assertEquals(self::EMAIL, $result['profile']['email']);
        $this->assertEquals('A test customer', $result['profile']['description']);
        $this->assertEquals('individual', $result['profile']['paymentProfiles']['customerType']);
        $this->assertEquals('Iain', $result['profile']['paymentProfiles']['billTo']['firstName']);
        $this->assertEquals('Cambridge', $result['profile']['paymentProfiles']['billTo']['lastName']);
        $this->assertEquals(self::STREETLINEONE, $result['profile']['paymentProfiles']['billTo']['address']);
        $this->assertEquals(self::CITY, $result['profile']['paymentProfiles']['billTo']['city']);
        $this->assertEquals(self::STATE, $result['profile']['paymentProfiles']['billTo']['state']);
        $this->assertEquals(self::COUNTRY, $result['profile']['paymentProfiles']['billTo']['country']);
        $this->assertEquals(self::POSTALCODE, $result['profile']['paymentProfiles']['billTo']['zip']);
        $this->assertEquals(self::PHONE, $result['profile']['paymentProfiles']['billTo']['phoneNumber']);
    }

    public function testBusinessMapping()
    {
        $address = new Address();
        $address->setStreetLineOne(self::STREETLINEONE)
            ->setStreetLineTwo(self::STREETLINETWO)
            ->setCity(self::CITY)
            ->setState(self::STATE)
            ->setCountryCode(self::COUNTRY)
            ->setPostalCode(self::POSTALCODE);

        $customer = new Customer();

        $customer
            ->setName(self::NAME)
            ->setEmail(self::EMAIL)
            ->setType(CustomerType::ORGANISATION)
            ->setPhone(self::PHONE)
            ->setAddress($address)
            ->setDescription('A test customer');

        $subject = new CustomerMapper();

        $result = $subject->mapCustomer($customer);

        $this->assertEquals(self::NAME, $result['name']);
        $this->assertEquals(self::EMAIL, $result['profile']['email']);
        $this->assertEquals('A test customer', $result['profile']['description']);
        $this->assertEquals('business', $result['profile']['paymentProfiles']['customerType']);
        $this->assertEquals(self::NAME, $result['profile']['paymentProfiles']['billTo']['company']);
        $this->assertEquals(self::STREETLINEONE, $result['profile']['paymentProfiles']['billTo']['address']);
        $this->assertEquals(self::CITY, $result['profile']['paymentProfiles']['billTo']['city']);
        $this->assertEquals(self::STATE, $result['profile']['paymentProfiles']['billTo']['state']);
        $this->assertEquals(self::COUNTRY, $result['profile']['paymentProfiles']['billTo']['country']);
        $this->assertEquals(self::POSTALCODE, $result['profile']['paymentProfiles']['billTo']['zip']);
        $this->assertEquals(self::PHONE, $result['profile']['paymentProfiles']['billTo']['phoneNumber']);
    }

    public function testSoleTraderMapping()
    {
        $address = new Address();
        $address->setStreetLineOne(self::STREETLINEONE)
            ->setStreetLineTwo(self::STREETLINETWO)
            ->setCity(self::CITY)
            ->setState(self::STATE)
            ->setCountryCode(self::COUNTRY)
            ->setPostalCode(self::POSTALCODE);

        $customer = new Customer();

        $customer
            ->setName(self::NAME)
            ->setEmail(self::EMAIL)
            ->setType(CustomerType::SOLE_TRADER)
            ->setPhone(self::PHONE)
            ->setAddress($address)
            ->setDescription('A test customer');

        $subject = new CustomerMapper();

        $result = $subject->mapCustomer($customer);

        $this->assertEquals(self::NAME, $result['name']);
        $this->assertEquals(self::EMAIL, $result['profile']['email']);
        $this->assertEquals('A test customer', $result['profile']['description']);
        $this->assertEquals('business', $result['profile']['paymentProfiles']['customerType']);
        $this->assertEquals(self::NAME, $result['profile']['paymentProfiles']['billTo']['company']);
        $this->assertEquals(self::STREETLINEONE, $result['profile']['paymentProfiles']['billTo']['address']);
        $this->assertEquals(self::CITY, $result['profile']['paymentProfiles']['billTo']['city']);
        $this->assertEquals(self::STATE, $result['profile']['paymentProfiles']['billTo']['state']);
        $this->assertEquals(self::COUNTRY, $result['profile']['paymentProfiles']['billTo']['country']);
        $this->assertEquals(self::POSTALCODE, $result['profile']['paymentProfiles']['billTo']['zip']);
        $this->assertEquals(self::PHONE, $result['profile']['paymentProfiles']['billTo']['phoneNumber']);
    }

    public function testSoleTraderMappingNoName()
    {
        $this->expectException(ValidationFailureException::class);
        $address = new Address();
        $address->setStreetLineOne(self::STREETLINEONE)
            ->setStreetLineTwo(self::STREETLINETWO)
            ->setCity(self::CITY)
            ->setState(self::STATE)
            ->setCountryCode(self::COUNTRY)
            ->setPostalCode(self::POSTALCODE);

        $customer = new Customer();

        $customer
            ->setEmail(self::EMAIL)
            ->setType(CustomerType::SOLE_TRADER)
            ->setPhone(self::PHONE)
            ->setAddress($address)
            ->setDescription('A test customer');

        $subject = new CustomerMapper();

        $result = $subject->mapCustomer($customer);
    }

    public function testSoleTraderMappingNoEmail()
    {
        $this->expectException(ValidationFailureException::class);
        $address = new Address();
        $address->setStreetLineOne(self::STREETLINEONE)
            ->setStreetLineTwo(self::STREETLINETWO)
            ->setCity(self::CITY)
            ->setState(self::STATE)
            ->setCountryCode(self::COUNTRY)
            ->setPostalCode(self::POSTALCODE);

        $customer = new Customer();

        $customer
            ->setName(self::NAME)
            ->setType(CustomerType::SOLE_TRADER)
            ->setPhone(self::PHONE)
            ->setAddress($address)
            ->setDescription('A test customer');

        $subject = new CustomerMapper();

        $result = $subject->mapCustomer($customer);

        $this->assertEquals(self::NAME, $result['name']);
        $this->assertEquals(self::EMAIL, $result['profile']['email']);
        $this->assertEquals('A test customer', $result['profile']['description']);
        $this->assertEquals('business', $result['profile']['paymentProfiles']['customerType']);
        $this->assertEquals(self::NAME, $result['profile']['paymentProfiles']['billTo']['company']);
        $this->assertEquals(self::STREETLINEONE, $result['profile']['paymentProfiles']['billTo']['address']);
        $this->assertEquals(self::CITY, $result['profile']['paymentProfiles']['billTo']['city']);
        $this->assertEquals(self::STATE, $result['profile']['paymentProfiles']['billTo']['state']);
        $this->assertEquals(self::COUNTRY, $result['profile']['paymentProfiles']['billTo']['country']);
        $this->assertEquals(self::POSTALCODE, $result['profile']['paymentProfiles']['billTo']['zip']);
        $this->assertEquals(self::PHONE, $result['profile']['paymentProfiles']['billTo']['phoneNumber']);
    }
}
