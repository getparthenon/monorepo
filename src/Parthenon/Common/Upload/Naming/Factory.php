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

namespace Parthenon\Common\Upload\Naming;

use Parthenon\Common\Exception\Upload\InvalidNamingStrategyException;

final class Factory implements FactoryInterface
{
    public function getStrategy(string $name): NamingStrategyInterface
    {
        switch ($name) {
            case NamingStrategyInterface::MD5_TIME:
                return new NamingMd5Time();
            case NamingStrategyInterface::RANDOM_TIME:
                return new RandomTime();
            default:
                throw new InvalidNamingStrategyException(sprintf("There is no naming strategy '%s'", $name));
        }
    }
}
