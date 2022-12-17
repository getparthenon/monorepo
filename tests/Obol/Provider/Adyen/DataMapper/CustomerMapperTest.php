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

namespace Tests\Obol\Providers\Adyen\DataMapper;

use Obol\Exception\MappingException;
use Obol\Models\Address;
use Obol\Models\Customer;
use Obol\Models\Enum\CustomerType;
use Obol\Provider\Adyen\DataMapper\CustomerMapper;
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

        $this->assertArrayHasKey('individual', $result);
        $this->assertEquals('individual', $result['type']);
        $this->assertEquals('Iain', $result['individual']['name']['firstName']);
        $this->assertEquals('Cambridge', $result['individual']['name']['lastName']);
        $this->assertEquals(self::EMAIL, $result['individual']['email']);
        $this->assertEquals(self::PHONE, $result['individual']['phone']);
        $this->assertEquals('A test customer', $result['individual']['description']);
        $this->assertEquals(self::STREETLINEONE, $result['individual']['residentialAddress']['street']);
        $this->assertEquals(self::STREETLINETWO, $result['individual']['residentialAddress']['street2']);
        $this->assertEquals(self::CITY, $result['individual']['residentialAddress']['city']);
        $this->assertEquals(self::STATE, $result['individual']['residentialAddress']['stateOrProvince']);
        $this->assertEquals(self::COUNTRY, $result['individual']['residentialAddress']['country']);
        $this->assertEquals(self::POSTALCODE, $result['individual']['residentialAddress']['postalCode']);
    }

    public function testCompanyMapping()
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

        $this->assertArrayHasKey('organization', $result);
        $this->assertEquals('organization', $result['type']);
        $this->assertEquals(self::EMAIL, $result['organization']['email']);
        $this->assertEquals(self::PHONE, $result['organization']['phone']);
        $this->assertEquals(self::NAME, $result['organization']['legalName']);
        $this->assertEquals('A test customer', $result['organization']['description']);
        $this->assertEquals(self::STREETLINEONE, $result['organization']['registeredAddress']['street']);
        $this->assertEquals(self::STREETLINETWO, $result['organization']['registeredAddress']['street2']);
        $this->assertEquals(self::CITY, $result['organization']['registeredAddress']['city']);
        $this->assertEquals(self::STATE, $result['organization']['registeredAddress']['stateOrProvince']);
        $this->assertEquals(self::COUNTRY, $result['organization']['registeredAddress']['country']);
        $this->assertEquals(self::POSTALCODE, $result['organization']['registeredAddress']['postalCode']);
    }

    public function testSoleTrader()
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

        $this->assertArrayHasKey('soleProprietorship', $result);
        $this->assertEquals('soleProprietorship', $result['type']);
        $this->assertEquals(self::NAME, $result['soleProprietorship']['name']);
        $this->assertEquals(self::STREETLINEONE, $result['soleProprietorship']['registeredAddress']['street']);
        $this->assertEquals(self::STREETLINETWO, $result['soleProprietorship']['registeredAddress']['street2']);
        $this->assertEquals(self::CITY, $result['soleProprietorship']['registeredAddress']['city']);
        $this->assertEquals(self::STATE, $result['soleProprietorship']['registeredAddress']['stateOrProvince']);
        $this->assertEquals(self::COUNTRY, $result['soleProprietorship']['registeredAddress']['country']);
        $this->assertEquals(self::COUNTRY, $result['soleProprietorship']['countryOfGoverningLaw']);
        $this->assertEquals(self::POSTALCODE, $result['soleProprietorship']['registeredAddress']['postalCode']);
    }


    public function testSoleTraderMissingCountryCode()
    {
        $this->expectException(MappingException::class);

        $address = new Address();
        $address->setStreetLineOne(self::STREETLINEONE)
            ->setStreetLineTwo(self::STREETLINETWO)
            ->setCity(self::CITY)
            ->setState(self::STATE)
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

    }
}
