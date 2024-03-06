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

namespace Smile\Onestock\Model\Handler\OrderUpdate;

use InvalidArgumentException;
use LogicException;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\CreditmemoManagementInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\CreditmemoFactory;
use Magento\Sales\Model\Order\CreditmemoRepository;
use Magento\Sales\Model\Order\Item;
use Smile\Onestock\Api\Data\Sales\OrderInterface as OnestockOrder;
use Smile\Onestock\Helper\OrderItem;

/**
 * Handler when an item is out of stock
 */
class Remove
{
    public function __construct(
        protected OrderItem $orderItemHelper,
        protected CreditmemoFactory $creditMemoFactory,
        protected SearchCriteriaBuilder $searchCriteriaBuilder,
        protected CreditmemoRepository $creditmemoRepository,
        protected CreditmemoManagementInterface $creditmemoManagement
    ) {
    }

    /**
     * Check if creditmemo already exists for this group
     */
    public function alreadyProcessed(string $groupId): bool
    {
        $withThisParcelId = $this->searchCriteriaBuilder
            ->addFilter('onestock_id', $groupId)
            ->create();
        return $this->creditmemoRepository->getList($withThisParcelId)->getTotalCount() > 0;
    }

    /**
     * Create creditmemo based on group
     *
     * @param string[] $group
     * @throws LogicException
     * @throws InvalidArgumentException
     * @throws LocalizedException
     */
    public function update(Order $order, OnestockOrder $onestockOrder, array $group): mixed
    {
        
        $qtys = [];
        $leftToRefund = $group['quantity'];
        foreach ($this->orderItemHelper->getItemBySku($order, $group['item_id']) as $orderItem) {
            /** @var Item $orderItem */
            $qtys[$orderItem->getId()] = min($leftToRefund, $orderItem->getQtyToRefund());
            $leftToRefund -= $qtys[$orderItem->getId()];
        }

        $message = __('Create refund from onestock');
        $toRefund = [
            'qtys' => $qtys,
            'reasons' => array_fill_keys(array_keys($qtys), $message),
        ];

        if (!$this->canRefundShippingFees($order, $toRefund)) {
            $toRefund['shipping_amount'] = 0;
        }

        $creditmemo = $this->creditMemoFactory
            ->createByOrder($order, $toRefund)
            ->setOnestockId($group['id']);
        $creditmemo->addComment($message);

        $this->creditmemoManagement->refund($creditmemo, true);

        return $creditmemo;
    }

    /**
     * Check if refund will be the last
     *
     * @param  Order $order    The order to check for refundability
     * @param  array $toRefund An array containing the quantity of items to refund
     * @return bool True if the order item can be refunded, false otherwise
     */
    public function canRefundShippingFees(Order $order, array $toRefund): bool
    {
        foreach ($order->getItems() as $orderItem) {
            /** @var Item $orderItem */
            if ($orderItem->canRefund()) {
                $qtyToRefund = $toRefund['qtys'][$orderItem->getId()] ?? 0;
                $qtyLeftToRefund = $orderItem->getQtyToRefund();
                if ((string) (float) $qtyToRefund != (string) (float) $qtyLeftToRefund) {
                    return false;
                }
            }
        }
        return true;
    }
}
