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

use Magento\AsynchronousOperations\Model\MassSchedule;
use Magento\Framework\Event\Observer;
use Psr\Log\LoggerInterface;
use Smile\Onestock\Api\Data\ConfigInterface;

/**
 * Observer to export order placed
 */
class ExportNewOrder extends AddOrderToExportQueue
{
    public function __construct(
        protected MassSchedule $asyncBulkPublisher,
        protected LoggerInterface $logger,
        protected ConfigInterface $config,
    ) {
        parent::__construct($asyncBulkPublisher, $logger);
    }

    /**
     * Add order to export queue.
     *
     * The queue will be processed asynchrously later
     * by route /V1/order/:orderId/onestock_export
     */
    public function execute(Observer $observer): void
    {
        if ($this->config->getOrderExportMode() == $observer->getEventName()) {
            parent::execute($observer);
        }
    }
}
