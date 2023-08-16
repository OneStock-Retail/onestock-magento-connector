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
use Smile\Onestock\Model\Mapping\Order as Mapping;
use Smile\Onestock\Service\Orders;

/**
 * Export order to onestock
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
class OrderExport implements OrderExportInterface
{
    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
        protected LoggerInterface $logger,
        protected Orders $service,
        protected Mapping $mapping
    ) {
    }

    /**
     * Export Order to Onestock
     */
    public function export(
        int $orderId
    ): void {
        $order = $this->orderRepository->get($orderId);
        $this->service->post($this->mapping->map($order));
    }
}
