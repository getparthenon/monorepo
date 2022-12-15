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

namespace App\Qa\AbTesting;

use Parthenon\AbTesting\Decider\EnabledDeciderInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Decider implements EnabledDeciderInterface
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function isTestable(): bool
    {
        $request = $this->requestStack->getCurrentRequest();

        if ('uptime_check' === $request->get('utm_source')) {
            return false;
        }

        if ('Mozilla/5.0' === $request->headers->get('User-Agent')) {
            return false;
        }

        return true;
    }
}
