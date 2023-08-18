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

/**
 * Represent One Task during Import
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
interface ShipmentImportHandlerInterface
{
    /**
     * Analyse line group in order to create a magento object
     *
     * @param array $onestockOrder
     * @param array $lineGroup
     * @return AbstractModel magento object to be saved
     * @throws LogicException
     * @throws InvalidArgumentException
     * @throws LocalizedException
     */
    public function update(Order $order, array $onestockOrder, array $lineGroup): AbstractModel;
}
