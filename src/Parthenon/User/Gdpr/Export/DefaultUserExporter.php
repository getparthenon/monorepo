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

namespace Parthenon\User\Gdpr\Export;

use Parthenon\User\Entity\UserInterface;

final class DefaultUserExporter implements ExporterInterface
{
    public function getName(): string
    {
        return 'user';
    }

    public function export(UserInterface $user): array
    {
        return [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
        ];
    }
}
