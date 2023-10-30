<?php
namespace Smile\OnestockDeliveryPromise\Plugin;

use Magento\Quote\Model\Quote\Item\AbstractItem;
use Magento\Quote\Model\Quote\Address;
use Magento\Quote\Model\Quote\Address\Rate;
use Smile\OnestockDeliveryPromise\Service\Promise;

class AddPromiseToRate
{
    public function __construct(
        protected Promise $service
    ) {
    }
    /**
     * Request shipping rates for entire address or specified address item
     */
    public function aroundRequestShippingRates(Address $subject, callable $proceed, AbstractItem $item = null):bool
    {
        $found = $proceed($item);
        $enabled = true;
        if (!$enabled) {
            return $found;
        }
        $shippingRates = $subject->getShippingRatesCollection();
        $methods = [];
        foreach ($shippingRates as $rate) {
            $methods[$rate->getCode()] = $rate;
        }
        if(empty($methods)) {
            return $found;
        }
        
        $products = [$item];
        $requests = [];
        if ($item == null) {
            $products = $subject->getAllItems();

        }
        foreach ($products as $product) {
            $requests[] = [
                "item_id" => $product->getSku(),
                "qty" => $product->getQty(),
            ];
        }
        foreach($this->service->get($requests, array_keys($methods), $subject->getCountryId()) as $promise) {
            if(!isset($methods[$promise->getDeliveryMethod()])) {
                continue;
            }
            /** @var Rate $rate */
            $rate = $methods[$promise->getDeliveryMethod()];
            $rate->setOnestockDp($promise);
        }
        return $found;
    }
}
