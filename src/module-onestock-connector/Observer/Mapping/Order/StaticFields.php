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

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Export order to onestock
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
class StaticFields implements ObserverInterface
{
    /**
     * Add order to export queue
     */
    public function execute(Observer $observer): void
    {
        $order = $observer->getSource();
        $onestockOrder = $observer->getTarget();
        
        $onestockOrder['date'] = strtotime($order->getCreatedAt());
    }
}
