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

namespace Smile\Onestock\Service;

use Magento\Sales\Api\OrderRepositoryInterface;
use Psr\Log\LoggerInterface;
use Smile\Onestock\Api\ShipmentImportInterface;
use Smile\Onestock\Api\Handler\ShipmentImportHandlerInterface;
use Smile\Onestock\Helper\CacheToken;
use Smile\Onestock\Model\Request\Orders as OrdersApi;
use Smile\Onestock\Api\Data\Sales\OrderInterface as OnestockOrderInterface;

/**
 * Service implementing the interface to update order.
 * 
 * Parcel and line_item_group from onestock will be imported as shipment and creditmemo
 */
class ShipmentImport implements ShipmentImportInterface
{
    /**
     * @param OrderRepositoryInterface $orderRepository 
     * @param LoggerInterface $logger 
     * @param OrdersApi $ordersApi 
     * @param CacheToken $tokenHelper 
     * @param ShipmentImportHandlerInterface[] $data 
     * @return void 
     */
    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
        protected LoggerInterface $logger,
        protected OrdersApi $ordersApi,
        protected CacheToken $tokenHelper,
        protected array $data = [],
    ) {
    }

    /**
     * Receive the Order Id that must be refreshed
     */
    public function requestUpdate(
        int $orderId
    ): void {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $this->orderRepository->get($orderId);
        $onestockOrder = $this->tokenHelper->call(function ($config, $token) use ($orderId): OnestockOrderInterface {
            return $this->ordersApi->get($config, $token, $orderId);
        });
        foreach ($onestockOrder['line_item_groups'] as $group) {
            if (!isset($this->data[$group['state']])) {
                continue;
            }
            $lineGroupHandler = $this->data[$group['state']];
            if (!$lineGroupHandler->alreadyProcessed($group['id'])) {
                $order->addRelatedObject($lineGroupHandler->update($order, $onestockOrder, $group));
            }
        }
        if (isset($onestockOrder['parcels'])) {
            foreach ($onestockOrder['parcels'] as $parcel) {
                $lineGroupHandler = $this->data['parcel'];
                if (!$lineGroupHandler->alreadyProcessed($parcel['id'])) {
                    $order->addRelatedObject($lineGroupHandler->update($order, $onestockOrder, $parcel));
                }
            }
        }
        
        $order->save();
    }
}
