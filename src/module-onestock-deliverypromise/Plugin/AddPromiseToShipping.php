<?php
namespace Smile\OnestockDeliveryPromise\Plugin;

use Magento\Quote\Api\Data\ShippingMethodExtensionFactory;

/**
 * Class AddPromiseToShipping
 */
class AddPromiseToShipping
{    
    
    public function __construct(
        protected ShippingMethodExtensionFactory $extensionFactory
    ) {
    }
    /**
     * Converts a specified rate model to a shipping method data object.
     *
     * @param string $quoteCurrencyCode The quote currency code.
     * @param \Magento\Quote\Model\Quote\Address\Rate $rateModel The rate model.
     * @return \Magento\Quote\Api\Data\ShippingMethodInterface Shipping method data object.
     */
    public function aroundModelToDataObject($subject, callable $proceed, $rateModel, $quoteCurrencyCode)
    {
        $shippingMethod = $proceed($rateModel, $quoteCurrencyCode);
        $enabled = true;
        if (!$enabled) {
            return $shippingMethod;
        }
        $extensionAttributes = $shippingMethod->getExtensionAttributes();
        if ($extensionAttributes == null) {
            $extensionAttributes = $this->extensionFactory->create();
        }
        $extensionAttributes->setOnestockDp(
            $rateModel->getOnestockDp()
        );
        $shippingMethod->setExtensionAttributes($extensionAttributes);
        return $shippingMethod;
    }
}
