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

namespace Smile\Onestock\Model\Handler\Shipment;

use InvalidArgumentException;
use LogicException;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\DataObject\Copy;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Sales\Api\ShipmentRepositoryInterface;
use Magento\Sales\Model\Convert\Order as ConvertOrder;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Item;
use Magento\Sales\Model\Order\Shipment;
use Smile\Onestock\Api\Data\Sales\OrderInterface as OnestockOrder;
use Smile\Onestock\Helper\OrderItem;

/**
 * Handler when a parcel is shipped
 */
class Parcel
{
    public function __construct(
        protected ConvertOrder $convertOrder,
        protected ShipmentRepositoryInterface $shipmentRepository,
        protected SearchCriteriaBuilder $searchCriteriaBuilder,
        protected Copy $objectCopyService,
        protected OrderItem $orderItemHelper
    ) {
    }

    /**
     * Reproduice same backoffice process to create a shipment
     *
     * @throws LogicException
     * @throws InvalidArgumentException
     */
    public function initShipment(Order $order): Shipment
    {
        /** @var Shipment $shipment */
        $shipment = $this->shipmentRepository
        ->create();

        $shipment->setOrder(
            $order
        )->setStoreId(
            $order->getStoreId()
        )->setCustomerId(
            $order->getCustomerId()
        )->setBillingAddressId(
            $order->getBillingAddressId()
        )->setShippingAddressId(
            $order->getShippingAddressId()
        );

        $this->objectCopyService->copyFieldsetToTarget('sales_convert_order', 'to_shipment', $order, $shipment);
        return $shipment;
    }

    /**
     * Check if shipment already exists for this group
     */
    public function alreadyProcessed(string $groupId): bool
    {
        $withThisParcelId = $this->searchCriteriaBuilder
            ->addFilter("onestock_id", $groupId)
            ->create();
        return $this->shipmentRepository->getList($withThisParcelId)->getTotalCount() > 0;
    }

    /**
     * Create shipment based on group
     *
     * @param string[] $parcel
     * @throws LogicException
     * @throws InvalidArgumentException
     * @throws LocalizedException
     */
    public function update(Order $order, OnestockOrder $onestockOrder, array $parcel): AbstractModel
    {
        /** @var \Magento\Sales\Model\Order\Shipment $shipment */
        $shipment = $this->initShipment($order);
        $shipmentQty = 0;

        foreach ($onestockOrder['line_item_groups'] as $group) {
            $shipmentItemQty = 0;
            /** @var Item $orderItem */
            if ($group['state'] == "fulfilled" && isset($group['parcel_id']) && $group['parcel_id'] == $parcel['id']) {
                foreach ($this->orderItemHelper->getItemBySku($order, $group['item_id']) as $orderItem) {
                    /** @var Item $orderItem */
                    $qtyShipped = min($orderItem->getQtyToShip(), $group['quantity'] - $shipmentItemQty);
                    $shipmentItemQty += $qtyShipped;
                    $shipmentItem = $this->convertOrder->itemToShipmentItem($orderItem)->setQty($qtyShipped);
                    $shipment->addItem($shipmentItem);
                }
            }
            $shipmentQty += $shipmentItemQty;
        }

        $shipment->setOnestockId($parcel["id"]);
        $shipment->setShipmentStatus(Shipment::STATUS_NEW);
        $shipment->setTotalQty($shipmentQty);
        $shipment->addComment(__("Create shipment from onestock"));
        $shipment->register();
        return  $shipment;
    }
}
