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

namespace Parthenon\Athena\Export;

use Parthenon\Athena\SectionManager;
use Parthenon\Export\DataProvider\DataProviderInterface;
use Parthenon\Export\ExportRequest;

class DefaultDataProvider implements DataProviderInterface
{
    public function __construct(private SectionManager $sectionManager)
    {
    }

    /**
     * @param AthenaExportRequest $exportRequest
     */
    public function getData(ExportRequest $exportRequest): iterable
    {
        $section = $this->sectionManager->getByUrlTag($exportRequest->getSectionUrlTag());

        $repository = $section->getRepository();
        $exportType = $exportRequest->getExportType();

        if ('all' == $exportType) {
            $results = $repository->getList($exportRequest->getParameters(), 'id', 'asc', -1);
        } else {
            $exportIds = $exportRequest->getParameters();
            $results = $repository->getByIds($exportIds);
        }

        return $results->getResults();
    }
}
