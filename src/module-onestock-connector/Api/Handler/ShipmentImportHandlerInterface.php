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

namespace Smile\Onestock\Api\Handler;

use Magento\Framework\Model\AbstractModel;
use Magento\Sales\Model\Order;
use Smile\Onestock\Api\Data\Sales\OrderInterface as OnestockOrder;

/**
 * Represent One Task during order update
 */
interface ShipmentImportHandlerInterface
{
    /**
     * Check if shipment already exists for this group
     *
     * @param string $groupId
     * @return bool
     */
    public function alreadyProcessed(string $groupId): bool;

    /**
     * Analyse line group in order to create a magento object
     *
     * @param Order $order
     * @param OnestockOrder $onestockOrder
     * @param string[] $lineGroup
     * @return AbstractModel
     */
    public function update(Order $order, OnestockOrder $onestockOrder, array $lineGroup): AbstractModel;
}
