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

namespace Smile\Onestock\Observer\Mapping\Order;

use Magento\Framework\DataObject\Copy;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Export order to onestock
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
class Items implements ObserverInterface
{
    public function __construct(
        protected Copy $objectCopyService,
    ) {
    }

    /**
     * Add order to export queue
     */
    public function execute(Observer $observer): void
    {
        $order = $observer->getSource();
        $target = $observer->getTarget();
        $target['order_items'] = array_map(
            function ($item) {
                $princingDetail = [
                    'pricing_details' => [
                        'price' => floatval($item->getRowTotalInclTax()),
                    ],
                ];
                $staticFields = [
                    'quantity' => intval($item->getQtyOrdered()),
                ];
                return $princingDetail + $this->objectCopyService->getDataFromFieldset(
                    'onestock_item_mapping',
                    'to_onestock_item',
                    $item
                ) + $staticFields;
            },
            $order->getAllItems()
        );
    }
}
