<?php
namespace Smile\OnestockDeliveryPromise\Plugin;

use Magento\Quote\Api\Data\ShippingMethodExtensionFactory;
use Smile\OnestockDeliveryPromise\Model\Data\Promise as PromiseDataModel;
use Magento\Framework\Webapi\ServiceInputProcessor;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class AddPromiseToShipping
 */
class AddPromiseToShipping
{    
    
    public function __construct(
        protected ShippingMethodExtensionFactory $extensionFactory,
        protected ServiceInputProcessor $toClassProcessor,
        protected Json $serializer,
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
        if (!$enabled || !$rateModel->getOnestockDp()) {
            return $shippingMethod;
        }
        $extensionAttributes = $shippingMethod->getExtensionAttributes();
        if ($extensionAttributes == null) {
            $extensionAttributes = $this->extensionFactory->create();
        }
        $extensionAttributes->setOnestockDp(
            $this->toClassProcessor->convertValue(
                [
                'data' => $this->serializer->unserialize($rateModel->getOnestockDp()),
                ],
                PromiseDataModel::class
            )
        );
        $shippingMethod->setExtensionAttributes($extensionAttributes);
        return $shippingMethod;
    }
}
