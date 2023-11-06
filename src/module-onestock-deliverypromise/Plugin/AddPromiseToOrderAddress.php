<?php
namespace Smile\OnestockDeliveryPromise\Plugin;

/**
 * Class AddPromiseToOrderAddress
 */
class AddPromiseToOrderAddress
{
    /**
     * Add promise to order address
     * (fieldset cannot be used because Magento\Quote\Model\Quote\Address\ToOrderAddress 
     *  is not looking for extension attribute among setter methods)
     *
     * @param EventObserver $observer
     * @return $this
     */
    public function aroundConvert($subject, $proceed, $address, $data){
        $orderAddress = $proceed($address, $data);
        $orderAddress->setOnestockDp($address->getOnestockDp());
        return $orderAddress;
    }

}
