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
use Smile\Onestock\Service\Orders as OrdersApi;

/**
 * Export order to onestock
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
class ShipmentImport implements ShipmentImportInterface
{
    /**
     * Constructor
     *
     * @param array $data
     * @return void
     */
    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
        protected LoggerInterface $logger,
        protected OrdersApi $ordersApi,
        protected array $data = [],
    ) {
    }

    /**
     * Receive the Order Id that must be refreshed
     */
    public function requestUpdate(
        int $orderId
    ): void {
        $order = $this->orderRepository->get($orderId);
        $onestockOrder = $this->ordersApi->get($orderId);
        foreach ($onestockOrder['line_item_groups'] as $group) {
            if (!isset($this->data[$group['state']])) {
                continue;
            }
            $lineGroupHandler = $this->data[$group['state']];
            if (!$lineGroupHandler->alreadyProcessed($group['id'])) {
                $order->addRelatedObject($lineGroupHandler->update($order, $onestockOrder, $group));
            }
        }
        foreach ($onestockOrder['parcels'] as $parcel) {
            $lineGroupHandler = $this->data['parcel'];
            if (!$lineGroupHandler->alreadyProcessed($parcel['id'])) {
                $order->addRelatedObject($lineGroupHandler->update($order, $onestockOrder, $parcel));
            }
        }
        
        $order->save();
    }
}
