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
use Smile\Onestock\Api\Sales\ShipmentImportInterface;

/**
 * Export order to onestock
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
class ShipmentImport implements ShipmentImportInterface
{
    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
        protected LoggerInterface $logger
    ) {
    }

    /**
     * Receive the Order Id that must be refreshed
     */
    public function requestUpdate(
        int $orderId
    ): void {
        $this->logger->debug("************************* ");
        $this->logger->debug("ShipmentImport " . $orderId);
        $this->orderRepository->get($orderId);
    }
}
