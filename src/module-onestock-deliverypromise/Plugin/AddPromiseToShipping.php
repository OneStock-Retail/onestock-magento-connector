<?php

declare(strict_types=1);

namespace Smile\OnestockDeliveryPromise\Plugin;

use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Webapi\ServiceInputProcessor;
use Magento\Quote\Api\Data\ShippingMethodExtensionFactory;
use Magento\Quote\Api\Data\ShippingMethodInterface;
use Magento\Quote\Model\Cart\ShippingMethodConverter;
use Magento\Quote\Model\Quote\Address\Rate;
use Smile\OnestockDeliveryPromise\Helper\Config;
use Smile\OnestockDeliveryPromise\Model\Data\Promise as PromiseDataModel;

/**
 * Add Promise To Extension attribute of Shipping
 */
class AddPromiseToShipping
{
    public function __construct(
        protected ShippingMethodExtensionFactory $extensionFactory,
        protected ServiceInputProcessor $toClassProcessor,
        protected Json $serializer,
        protected Config $config,
    ) {
    }
    /**
     * Converts a specified rate model to a shipping method data object
     */
    public function aroundModelToDataObject(
        ShippingMethodConverter $subject,
        callable $proceed,
        Rate $rateModel,
        string $quoteCurrencyCode
    ): ShippingMethodInterface {
        $shippingMethod = $proceed($rateModel, $quoteCurrencyCode);

        if (!$this->config->isEnabled() || !$rateModel->getOnestockDp()) {
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
