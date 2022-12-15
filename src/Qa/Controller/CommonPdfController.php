<?php

declare(strict_types=1);

/*
 * Copyright Iain Cambridge 2020-2022.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: 16.12.2025
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace App\Qa\Controller;

use Parthenon\Common\Pdf\GeneratorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommonPdfController
{
    #[Route('/api/qa/common/pdf', name: 'app_qa_common_pdf')]
    public function downloadPdf(GeneratorInterface $generator)
    {
        $pdf = $generator->generate('<b>hello world</b>');

        return new Response($pdf, headers: ['Content-type' => 'application/pdf']);
    }
}
