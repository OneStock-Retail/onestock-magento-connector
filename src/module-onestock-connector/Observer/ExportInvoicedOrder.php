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

namespace Smile\Onestock\Observer;

use Magento\Framework\Event\Observer;

/**
 * Observer to export order placed
 */
class ExportInvoicedOrder extends AddOrderToExportQueue
{
    /**
     * Add order to export queue.
     *
     * The queue will be processed asynchrously later
     * by route /V1/order/:orderId/onestock_export
     */
    public function execute(Observer $observer): void
    {
        $observer->setOrder($observer->getInvoice()->getOrder());
        parent::execute($observer);
    }
}
