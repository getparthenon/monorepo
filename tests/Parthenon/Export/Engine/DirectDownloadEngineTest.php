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

use Parthenon\Export\Exception\InvalidDataProviderException;
use Parthenon\Export\Exporter\ExporterInterface;
use Parthenon\Export\Exporter\ExporterManagerInterface;
use Parthenon\Export\ExportRequest;
use Parthenon\Export\NormaliserInterface;
use Parthenon\Export\NormaliserManagerInterface;
use PHPUnit\Framework\TestCase;

class DirectDownloadEngineTest extends TestCase
{
    public function testCallNormaliserThenExporter()
    {
        $exporter = $this->createMock(ExporterInterface::class);
        $exporterManager = $this->createMock(ExporterManagerInterface::class);

        $normaliser = $this->createMock(NormaliserInterface::class);
        $normaliserManager = $this->createMock(NormaliserManagerInterface::class);

        $data = [0, 1, 2, 3];
        $dataProvider = function () use ($data) {
            return $data;
        };

        $exportRequest = $this->createMock(ExportRequest::class);
        $exportRequest->method('getDataProvider')->willReturn($dataProvider);
        $exportRequest->method('getId')->willreturn('random-export');

        $exporterManager->method('getExporter')->with($exportRequest)->willReturn($exporter);

        $normaliserManager->expects($this->once())->method('getNormaliser')->with($data)->willReturn($normaliser);

        $normalisedData = [4, 5, 6, 7];
        $normaliser->expects($this->once())->method('normalise')->with($data)->willReturn($normalisedData);

        $exportData = 'Export data';

        $exporter->expects($this->once())->method('getOutput')->with($normalisedData)->willReturn($exportData);

        $subject = new DirectDownloadEngine($normaliserManager, $exporterManager);
        $subject->process($exportRequest);
    }

    public function testThrowExceptionInvalidDataProvider()
    {
        $this->expectException(InvalidDataProviderException::class);
        $exporter = $this->createMock(ExporterInterface::class);
        $exporterManager = $this->createMock(ExporterManagerInterface::class);

        $normaliser = $this->createMock(NormaliserInterface::class);
        $normaliserManager = $this->createMock(NormaliserManagerInterface::class);

        $data = [0, 1, 2, 3];
        $dataProvider = function () { return null; };

        $exportRequest = $this->createMock(ExportRequest::class);
        $exportRequest->method('getDataProvider')->willReturn($dataProvider);
        $exportRequest->method('getId')->willreturn('random-export');

        $exporterManager->method('getExporter')->with($exportRequest)->willReturn($exporter);

        $normaliserManager->method('getNormaliser')->with($data)->willReturn($normaliser);

        $normalisedData = [4, 5, 6, 7];
        $normaliser->method('normalise')->with($data)->willReturn($normalisedData);

        $exportData = 'Export data';

        $exporter->method('getOutput')->with($normalisedData)->willReturn($exportData);

        $subject = new DirectDownloadEngine($normaliserManager, $exporterManager);
        $subject->process($exportRequest);
    }
}
