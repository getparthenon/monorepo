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

use Parthenon\Export\Entity\BackgroundExportRequest;
use Parthenon\Export\ExportRequest;
use Parthenon\Export\ExportResponseInterface;
use Parthenon\Export\Repository\BackgroundExportRequestRepositoryInterface;
use Parthenon\Export\Response\WaitingResponse;
use Symfony\Component\Messenger\MessageBusInterface;

final class BackgroundDownloadEngine implements EngineInterface
{
    public const NAME = 'background_download';

    public function __construct(private MessageBusInterface $messengerBus, private BackgroundExportRequestRepositoryInterface $backgroundExportRequestRepository)
    {
    }

    public function process(ExportRequest $exportRequest): ExportResponseInterface
    {
        $backgroundExportRequest = BackgroundExportRequest::createFromExportRequest($exportRequest);

        $this->backgroundExportRequestRepository->save($backgroundExportRequest);
        $this->messengerBus->dispatch($backgroundExportRequest);

        return new WaitingResponse((string) $backgroundExportRequest->getId());
    }
}
