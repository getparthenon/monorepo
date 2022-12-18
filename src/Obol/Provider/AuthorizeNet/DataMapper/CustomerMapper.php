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

namespace Obol\Provider\AuthorizeNet\DataMapper;

use Obol\Models\Customer;
use Obol\Models\Enum\CustomerType;

class CustomerMapper
{
    public function mapCustomer(Customer $customer): array
    {
        $billTo = [
            'address' => $customer->getAddress()->getStreetLineOne(),
            'city' => $customer->getAddress()->getCity(),
            'state' => $customer->getAddress()->getState(),
            'zip' => $customer->getAddress()->getPostalCode(),
            'country' => $customer->getAddress()->getCountryCode(),
            'phoneNumber' => $customer->getPhone(),
        ];

        if (CustomerType::INDIVIDUAL === $customer->getType()) {
            [$firstName, $lastName] = explode(' ', $customer->getName(), 2);
            $billTo['firstName'] = $firstName;
            $billTo['lastName'] = $lastName;
        } else {
            $billTo['company'] = $customer->getName();
        }

        return [
            'name' => $customer->getName(),
            'profile' => [
                'description' => $customer->getDescription(),
                'email' => $customer->getEmail(),
                'paymentProfiles' => [
                    'customerType' => CustomerType::INDIVIDUAL === $customer->getType() ? 'individual' : 'business',
                    'billTo' => $billTo,
                ],
                'shipToList' => [$billTo], // The docs say this is in the root. But https://api.authorize.net/xml/v1/schema/AnetApiSchema.xsd disagrees and that wins out.
            ],
        ];
    }
}
