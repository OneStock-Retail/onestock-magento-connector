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
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Sales\Model\Order;

/**
 * Import creditmemo from onestock
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
class Remove
{
    /**
     * Check if shipment already exists for this group
     */
    public function alreadyProcessed(string $groupId): bool
    {
        //TODO check if already exists
        return true;
    }

    /**
     * Create creditmemo based on group
     *
     * @param array $onestockOrder
     * @param array $group
     * @throws LogicException
     * @throws InvalidArgumentException
     * @throws LocalizedException
     */
    public function update(Order $order, array $onestockOrder, array $group): AbstractModel
    {
        return null;
    }
}
