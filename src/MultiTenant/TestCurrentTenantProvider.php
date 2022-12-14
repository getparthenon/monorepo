<?php

declare(strict_types=1);

/*
 * Copyright Iain Cambridge 2020-2023.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: TBD ( 3 years after 2.2.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace App\MultiTenant;

use Parthenon\MultiTenancy\Entity\Tenant;
use Parthenon\MultiTenancy\Entity\TenantInterface;
use Parthenon\MultiTenancy\TenantProvider\TenantProviderInterface;

class TestCurrentTenantProvider implements TenantProviderInterface
{
    public function getCurrentTenant(bool $refresh = false): Tenant
    {
        $tenant = new Tenant();
        $tenant->setSubdomain('test');
        $tenant->setDatabase('parthenon_tenant_test');

        return $tenant;
    }

    public function setTenant(TenantInterface $tenant): void
    {
        // TODO: Implement setTenant() method.
    }
}
