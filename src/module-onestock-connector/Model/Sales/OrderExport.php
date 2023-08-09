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

namespace Smile\Onestock\Model\Sales;

use Magento\Sales\Api\OrderRepositoryInterface;
use Psr\Log\LoggerInterface;
use Smile\Onestock\Api\Sales\OrderExportInterface;

/**
 * Export order to onestock
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
class OrderExport implements OrderExportInterface
{
    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
        protected LoggerInterface $logger
    ) {
    }

    /**
     * Export Order to Onestock
     */
    public function export(
        int $orderId
    ): void {
        $this->logger->debug("************************* ");
        $this->logger->debug("OrderExport " . $orderId);
        $this->orderRepository->get($orderId);
    }
}
