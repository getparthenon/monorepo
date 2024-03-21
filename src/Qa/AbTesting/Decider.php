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
