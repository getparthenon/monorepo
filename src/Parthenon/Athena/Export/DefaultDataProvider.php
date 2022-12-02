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

use Parthenon\Athena\Filters\FilterManager;
use Parthenon\Athena\Filters\ListFilters;
use Parthenon\Athena\SectionManager;
use Parthenon\Export\DataProvider\DataProviderInterface;
use Parthenon\Export\ExportRequest;

class DefaultDataProvider implements DataProviderInterface
{
    public function __construct(
        private SectionManager $sectionManager,
        private FilterManager $filterManager
    ) {
    }

    public function getData(ExportRequest $exportRequest): iterable
    {
        $parameters = $exportRequest->getParameters();
        // TODO add sanity checks
        // TODO catch exceptions

        $section = $this->sectionManager->getByUrlTag($parameters['section_url_tag']);
        $repository = $section->getRepository();
        $exportType = $parameters['export_type'];

        if ('all' == $exportType) {
            $listFilters = $section->buildFilters(new ListFilters($this->filterManager));

            $filters = $listFilters->getFilters($parameters['search']);
            $results = $repository->getList($filters, 'id', 'asc', -1);
        } else {
            $results = $repository->getByIds($parameters['search']);
        }

        return $results->getResults();
    }
}
