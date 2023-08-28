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

use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Sales\Api\Data\OrderInterface;

/**
 * Helper for orders
 */
class OrderItem
{
    /**
     * Loop for item by sku
     *
     * @return OrderItemInterface[]
     */
    public function getItemBySku(OrderInterface $order, string $sku): array
    {
        return array_filter($order->getItems(), function ($item) use ($sku) {
            return $sku == $item->getSku() && !$item->getParentItem();
        });
    }
}
