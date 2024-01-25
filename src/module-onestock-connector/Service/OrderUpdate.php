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

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Psr\Log\LoggerInterface;
use Smile\Onestock\Api\Data\Sales\OrderInterface as OnestockOrderInterface;
use Smile\Onestock\Api\Handler\OrderUpdateHandlerInterface;
use Smile\Onestock\Api\OrderUpdateInterface;
use Smile\Onestock\Helper\CacheToken;
use Smile\Onestock\Model\Request\Orders as OrdersApi;

/**
 * Service implementing the interface to update order.
 *
 * Parcel and line_item_group from onestock will be imported as shipment and creditmemo
 */
class OrderUpdate implements OrderUpdateInterface
{
    /**
     * @param OrderUpdateHandlerInterface[] $data
     */
    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
        protected SearchCriteriaBuilder $searchCriteriaBuilder,
        protected LoggerInterface $logger,
        protected OrdersApi $ordersApi,
        protected CacheToken $tokenHelper,
        protected array $data = []
    ) {
    }

    /**
     * @inheritdoc
     */
    public function requestUpdate(string $orderIncrementId, int $date, string $oldState, string $newState): void
    {
        $order = $this->getOrderByIncrementId($orderIncrementId);
        $onestockOrder = $this->tokenHelper->call(
            function ($config, $token) use ($orderIncrementId): OnestockOrderInterface {
                return $this->ordersApi->get($config, $token, $orderIncrementId);
            }
        );
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
        
        $this->orderRepository->save($order);
    }

    /**
     * Load one order by increment
     *
     * @throws NoSuchEntityException
     */
    public function getOrderByIncrementId(string $orderIncrementId): Order
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('increment_id', $orderIncrementId)
            ->setPageSize(1)
            ->create();
        $listing = $this->orderRepository->getList($searchCriteria)->getItems();
        if (empty($listing)) {
            throw new NoSuchEntityException(
                __("The entity that was requested doesn't exist. Verify the entity and try again.")
            );
        }
        /** @var Order $order */
        $order = reset($listing);
        return $order;
    }
}
