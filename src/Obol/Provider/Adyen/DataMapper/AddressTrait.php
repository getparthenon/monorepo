<?php

declare(strict_types=1);

/*
 * Copyright (C) 2020-2024 Iain Cambridge
 *
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Obol\Provider\Adyen\DataMapper;

use Obol\Model\Address;

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
            //       'street2' => $address->getStreetLineTwo(),
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
