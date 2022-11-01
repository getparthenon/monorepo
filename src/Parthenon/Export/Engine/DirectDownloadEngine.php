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

use Parthenon\Export\Exporter\ExporterInterface;
use Parthenon\Export\ExportRequest;
use Parthenon\Export\ExportResponse;
use Parthenon\Export\NormaliserInterface;

class DirectDownloadEngine implements EngineInterface
{
    public function __construct(
        private NormaliserInterface $normaliser,
        private ExporterInterface $exporter,
    ) {
    }

    public function process(ExportRequest $exportRequest): ExportResponse
    {
        $data = $exportRequest->getDataProvider()->__invoke();

        if (is_array($data)) {
            throw new \InvalidArgumentException('Data provider must return an array');
        }

        $normalisedData = $this->normaliser->normalise($data);
        $exportedContent = $this->exporter->getOutput($normalisedData);
    }
}
