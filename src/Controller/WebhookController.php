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

namespace App\Controller;

use Parthenon\Billing\Webhook\RequestProcessor;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class WebhookController
{
    #[Route('/webhook', name: 'app_webhook')]
    public function authenticate(Request $request, RequestProcessor $requestProcessor, LoggerInterface $logger)
    {
        $requestProcessor->processRequest($request);

        return new JsonResponse([]);
    }
}
