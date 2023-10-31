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

namespace Smile\OnestockDeliveryPromise\Api;

interface ShipmentInterface
{
    /**
     * Estimate shipping by sku
     * @param string $sku
     * @param string $country
     * @return \Magento\Quote\Api\Data\ShippingMethodInterface[] An array of shipping methods
     */
    public function estimate($sku, $country = "");
}
