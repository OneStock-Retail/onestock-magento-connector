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

namespace Smile\Onestock\Helper;

use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Item;

/**
 * Export order to onestock
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
class OrderItem
{
    /**
     * Loop for item by sku
     *
     * @return Item[]
     */
    public function getItemBySku(Order $order, string $sku): array
    {
        return array_filter($order->getItems(), function ($item) use ($sku) {
            return $sku == $item->getSku() && !$item->getParentItem();
        });
    }
}
