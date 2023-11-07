<?php

declare(strict_types=1);

namespace Smile\OnestockDeliveryPromise\Plugin;

use Magento\Quote\Model\Quote\Address;
use Magento\Quote\Model\Quote\Address\ToOrderAddress;

/**
 * Add Promise To An Order Address field
 */
class AddPromiseToOrderAddress
{
    /**
     * Add promise to order address
     * (fieldset cannot be used because Magento\Quote\Model\Quote\Address\ToOrderAddress
     *  is not looking for extension attribute among setter methods)
     *
     * @param array $data
     * @return $this
     */
    public function aroundConvert(ToOrderAddress $subject, callable $proceed, Address $address, array $data)
    {
        $orderAddress = $proceed($address, $data);
        $orderAddress->setOnestockDp($address->getOnestockDp());
        return $orderAddress;
    }
}
