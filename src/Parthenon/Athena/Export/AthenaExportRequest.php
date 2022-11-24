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

use Parthenon\Export\ExportRequest;

class AthenaExportRequest extends ExportRequest
{
    public function __construct(string $id, string $exportFormat, string $dataProviderServiceService, array $parameters, private string $sectionUrlTag, private string $exportType)
    {
        parent::__construct($id, $exportFormat, $dataProviderServiceService, $parameters);
    }

    public function getSectionUrlTag(): string
    {
        return $this->sectionUrlTag;
    }

    public function getExportType(): string
    {
        return $this->exportType;
    }
}