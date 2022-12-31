<?php

declare(strict_types=1);

/*
 * Copyright Iain Cambridge 2020-2023.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: TBD ( 3 years after 2.2.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace Obol\Exception;

use Psr\Http\Message\RequestInterface;

class BadAuthFailedRequestException extends FailedRequestException
{
    public function __construct(RequestInterface $request, string $message = 'Bad auth', int $code = 0, ?\Throwable $exception = null)
    {
        parent::__construct($request, $message, $code, $exception);
    }
}
