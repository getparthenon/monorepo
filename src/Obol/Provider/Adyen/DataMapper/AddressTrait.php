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

use Obol\Models\Address;

trait AddressTrait
{
    public function mapAddress(Address $address): array
    {
        return [
            'city' => $address->getCity(),
            'country' => $address->getCountryCode(),
            'postalCode' => $address->getPostalCode(),
            'stateOrProvince' => $address->getState(),
            'street' => $address->getStreetLineOne(),
            'street2' => $address->getStreetLineTwo(),
        ];
    }

    public function mapToAddress(array $data): Address
    {
        $address = new Address();

        if (isset($data['city'])) {
            $address->setCity($data['city']);
        }
        if (isset($data['country'])) {
            $address->setCountryCode($data['country']);
        }
        if (isset($data['postalCode'])) {
            $address->setPostalCode($data['postalCode']);
        }
        if (isset($data['stateOrProvince'])) {
            $address->setState($data['stateOrProvince']);
        }
        if (isset($data['street'])) {
            $address->setStreetLineOne($data['street']);
        }
        if (isset($data['street2'])) {
            $address->setStreetLineTwo($data['street2']);
        }

        return $address;
    }
}
