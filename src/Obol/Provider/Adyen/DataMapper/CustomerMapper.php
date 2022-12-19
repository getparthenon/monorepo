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

namespace Obol\Provider\Adyen\DataMapper;

use Obol\Exception\MappingException;
use Obol\Exception\ValidationFailureException;
use Obol\Models\Customer;
use Obol\Models\Enum\CustomerType;
use Obol\Models\ValidationError;

class CustomerMapper implements CustomerMapperInterface
{
    use AddressTrait;

    /**
     * @throws ValidationFailureException
     * @throws MappingException
     */
    public function mapFromCustomer(Customer $customer): array
    {
        $validationErrors = [];

        if (!$customer->hasName()) {
            $validationErrors[] = new ValidationError('name', 'Adyen requires the name for a customer');
        }

        if (!$customer->getAddress()->hasCountryCode()) {
            $validationErrors[] = new ValidationError('address.country_code', 'Adyen requires a country code in the address for a customer');
        }

        if (!empty($validationErrors)) {
            throw ValidationFailureException::createWithErrors($validationErrors);
        }

        if (CustomerType::INDIVIDUAL === $customer->getType()) {
            return $this->mapIndividual($customer);
        }

        if (CustomerType::ORGANISATION === $customer->getType()) {
            return $this->mapOrganisation($customer);
        }

        if (CustomerType::SOLE_TRADER === $customer->getType()) {
            return $this->mapSoleProprietorship($customer);
        }

        throw new MappingException('Invalid customer type');
    }

    public function mapToCustomer(array $data): Customer
    {
        $customer = new Customer();

        if ('individual' === $data['type']) {
            $customer->setType(CustomerType::INDIVIDUAL);
            $customer->setAddress($this->mapToAddress($data['individual']['residentialAddress']));
            $customer->setName($data['individual']['firstName'].' '.$data['individual']['lastName']);
            $this->handlePersonalData($customer, $data['individual']);
        } elseif ('organization' === $data['type']) {
            $customer->setType(CustomerType::ORGANISATION);
            $customer->setAddress($this->mapToAddress($data['organization']['residentialAddress']));
            $customer->setName($data['organization']['legalName']);
            $this->handlePersonalData($customer, $data['organization']);
        } elseif ('soleProprietorship' === $data['type']) {
            $customer->setType(CustomerType::SOLE_TRADER);
            $customer->setAddress($this->mapToAddress($data['soleProprietorship']['residentialAddress']));
            $customer->setName($data['organization']['name']);
        }

        return $customer;
    }

    protected function mapIndividual(Customer $customer): array
    {
        [$firstName, $lastName] = explode(' ', $customer->getName(), 2);

        return [
            'type' => 'individual',
            'individual' => [
                'residentialAddress' => $this->mapAddress($customer->getAddress()),
                'name' => [
                    'firstName' => $firstName,
                    'lastName' => $lastName,
                ],
                'email' => $customer->getEmail(),
                'phone' => $customer->getPhone(),
                'description' => $customer->getDescription(),
            ],
        ];
    }

    protected function mapOrganisation(Customer $customer): array
    {
        return [
            'type' => 'organization',
            'organization' => [
                'registeredAddress' => $this->mapAddress($customer->getAddress()),
                'email' => $customer->getEmail(),
                'phone' => $customer->getPhone(),
                'description' => $customer->getDescription(),
                'legalName' => $customer->getName(),
            ],
        ];
    }

    protected function mapSoleProprietorship(Customer $customer): array
    {
        return [
            'type' => 'soleProprietorship',
            'soleProprietorship' => [
                'registeredAddress' => $this->mapAddress($customer->getAddress()),
                'name' => $customer->getName(),
                'countryOfGoverningLaw' => $customer->getAddress()->getCountryCode(),
            ],
        ];
    }

    protected function handlePersonalData(Customer $customer, array $data): void
    {
        if (isset($data['email'])) {
            $customer->setEmail($data['email']);
        }
        if (isset($data['phone'])) {
            $customer->setPhone($data['phone']);
        }
        if (isset($data['description'])) {
            $customer->setDescription($data['description']);
        }
    }
}
