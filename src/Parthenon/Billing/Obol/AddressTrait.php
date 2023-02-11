<?php

namespace Parthenon\Billing\Obol;

use Obol\Model\Address as ObolAddress;
use Parthenon\Billing\Entity\CustomerInterface;

trait AddressTrait
{

    /**
     * @param CustomerInterface $customer
     * @return ObolAddress
     */
    public function buildAddresss(CustomerInterface $customer): ObolAddress
    {
        $address = new ObolAddress();
        $address->setStreetLineOne($customer->getBillingAddress()->getStreetLineOne());
        $address->setStreetLineTwo($customer->getBillingAddress()->getStreetLineTwo());
        $address->setCity($customer->getBillingAddress()->getCity());
        $address->setState($customer->getBillingAddress()->getRegion());
        $address->setCountryCode($customer->getBillingAddress()->getCountry());
        $address->setPostalCode($customer->getBillingAddress()->getPostcode());
        return $address;
    }
}