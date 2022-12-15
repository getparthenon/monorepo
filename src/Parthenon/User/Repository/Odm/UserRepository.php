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

namespace Parthenon\User\Repository\Odm;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Parthenon\User\Entity\User;

class UserRepository extends ServiceDocumentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
}
