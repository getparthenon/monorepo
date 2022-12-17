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

use Obol\Models\Customer;
use Obol\Models\Enum\CustomerType;

class CustomerMapper
{
    use AddressTrait;

    public function mapCustomer(Customer $customer): array
    {
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
}
