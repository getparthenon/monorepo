<?php

declare(strict_types=1);

/*
 * Copyright (C) 2020-2024 Iain Cambridge
 *
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Parthenon\Billing\Entity\CustomerInterface;
use Parthenon\Billing\Entity\EmbeddedSubscription;
use Parthenon\Common\Address;
use Parthenon\User\Entity\MemberInterface;

#[ORM\Entity()]
#[ORM\Table('teams')]
class Team extends \Parthenon\User\Entity\Team implements CustomerInterface
{
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'team')]
    protected Collection $members;

    #[ORM\Embedded(class: Address::class)]
    protected Address $billingAddress;

    #[ORM\Column(name: 'external_customer_reference', nullable: true)]
    protected ?string $externalCustomerReference;

    #[ORM\Column(name: 'payment_provider_details_url', nullable: true)]
    protected ?string $paymentProviderDetailsUrl;

    #[ORM\Embedded(class: EmbeddedSubscription::class)]
    private ?EmbeddedSubscription $subscription;

    public function setSubscription(EmbeddedSubscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public function getSubscription(): EmbeddedSubscription
    {
        return $this->subscription;
    }

    public function hasActiveSubscription(): bool
    {
        if (!$this->subscription) {
            return false;
        }

        return $this->subscription->isActive();
    }

    public function getIdentifier(): string
    {
        return (string) $this->getName();
    }

    public function hasSubscription(): bool
    {
        return isset($this->subscription);
    }

    public function setBillingAddress(Address $address)
    {
        $this->billingAddress = $address;
    }

    public function getBillingAddress(): Address
    {
        return $this->billingAddress;
    }

    public function hasBillingAddress(): bool
    {
        return isset($this->billingAddress);
    }

    public function setExternalCustomerReference($customerReference)
    {
        $this->externalCustomerReference = $customerReference;
    }

    public function getExternalCustomerReference()
    {
        return $this->externalCustomerReference;
    }

    public function getBillingEmail()
    {
        /** @var MemberInterface $member */
        $member = $this->members->first();

        return $member->getEmail();
    }

    public function getDisplayName(): string
    {
        return $this->getName();
    }

    public function setPaymentProviderDetailsUrl(?string $url)
    {
        $this->paymentProviderDetailsUrl = $url;
    }

    public function getPaymentProviderDetailsUrl()
    {
        return $this->paymentProviderDetailsUrl;
    }

    public function hasExternalCustomerReference(): bool
    {
        return isset($this->externalCustomerReference);
    }
}
