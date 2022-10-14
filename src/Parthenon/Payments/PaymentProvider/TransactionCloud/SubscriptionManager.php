<?php

declare(strict_types=1);

/*
 * Copyright Humbly Arrogant Ltd 2020-2022.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: TBD ( 3 years after 2.1.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace Parthenon\Payments\PaymentProvider\TransactionCloud;

use Parthenon\Payments\Entity\Subscription;
use Parthenon\Payments\SubscriptionManagerInterface;
use TransactionCloud\TransactionCloud;

class SubscriptionManager implements SubscriptionManagerInterface
{
    public function __construct(private TransactionCloud $transactionCloud)
    {
    }

    public function cancel(Subscription $subscription)
    {
        // TODO: Implement cancel() method.
    }

    public function change(Subscription $subscription)
    {
        // TODO: Implement change() method.
    }

    public function syncStatus(Subscription $subscription): Subscription
    {
        // TODO: Implement syncStatus() method.
    }

    public function getInvoiceUrl(Subscription $subscription)
    {
        // TODO: Implement getInvoiceUrl() method.
    }

    public function getBillingPortal(Subscription $subscription): string
    {
        return $this->transactionCloud->getUrlToManageTransactions($subscription->getCustomerId());
    }
}
