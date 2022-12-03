<?php

declare(strict_types=1);

/*
 * Copyright Iain Cambridge 2020-2022.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: TBD ( 3 years after 2.1.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace Parthenon\Export\Engine;

use Parthenon\Export\BackgroundEmailExportRequest;
use Parthenon\Export\ExportRequest;
use Parthenon\Export\ExportResponseInterface;
use Parthenon\Export\Response\EmailResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Security;

final class BackgroundEmailEngine implements EngineInterface
{
    public const NAME = 'background_email';

    public function __construct(private Security $security, private MessageBusInterface $messengerBus)
    {
    }

    public function process(ExportRequest $exportRequest): ExportResponseInterface
    {
        $backgroundEmail = BackgroundEmailExportRequest::createFromExportRequest($exportRequest, $this->security->getUser());
        $this->messengerBus->dispatch($backgroundEmail);

        return new EmailResponse();
    }
}
