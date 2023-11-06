<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade to newer
 * versions in the future.
 *
 * @author    Pascal Noisette <pascal.noisette@smile.fr>
 * @copyright 2023 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

declare(strict_types=1);

namespace Smile\OnestockDeliveryPromise\Service;

use Magento\Catalog\Model\ProductFactory;
use Magento\Quote\Model\QuoteFactory;
use Magento\Quote\Model\Quote\AddressFactory;
use Magento\Quote\Model\Quote\ItemFactory;
use Smile\OnestockDeliveryPromise\Api\ShipmentInterface;
use Smile\OnestockDeliveryPromise\Helper\Config;
use Magento\Quote\Model\Cart\ShippingMethodConverter;

/**
 * Service implementing the interface to get promise
 */
class Promise implements ShipmentInterface
{

    public function __construct(
        protected Config $config,
        protected AddressFactory $addressFactory,
        protected QuoteFactory $quoteFactory,
        protected ItemFactory $quoteItemFactory,
        protected ShippingMethodConverter $converter,
        protected ProductFactory $productFactory,
    ) {
    }

    /**
     * Estimate shipping by sku
     * @param string $sku
     * @param string $country
     * @return \Magento\Quote\Api\Data\ShippingMethodInterface[] An array of shipping methods
     */
    public function estimate($sku, $country = "")
    {
        if (empty($country)) {
            $country = $this->config->getGuestCountry();
        }
        $temporaryAddress = $this->addressFactory->create();
        $temporaryProduct = $this->productFactory->create();
        $temporaryItem = $this->quoteItemFactory->create()->setQty(1)->setSku($sku)->setData('product', $temporaryProduct);
        $temporaryAddress->setData(
            [
                'collect_shipping_rates' => true,
                'cached_items_all' => [$temporaryItem],
                'country_id' => $country,
            ]
        );
        $temporaryAddress->setQuote($this->quoteFactory->create()->setShippingAddress($temporaryAddress));
        $temporaryItem->setQuote($temporaryAddress->getQuote());
        $temporaryAddress->collectShippingRates();
        $shippingRates = $temporaryAddress->getGroupedAllShippingRates();
        foreach ($shippingRates as $carrierRates) {
            foreach ($carrierRates as $rate) {
                $output[] = $this->converter->modelToDataObject($rate, $this->config->getBaseCurrencyCode());
            }
        }
        return $output;
    }
}
