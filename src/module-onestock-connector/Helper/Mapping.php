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

namespace Smile\Onestock\Helper;

use InvalidArgumentException;
use Magento\Framework\DataObject\Copy;

/**
 * Apply mapping between magento and onestock structure
 */
class Mapping
{
    public function __construct(
        protected Copy $objectCopyService
    ) {
    }

    /**
     * Convert order to a structure compatible for onestock webservice
     * @return \ArrayObject[]
     * @throws InvalidArgumentException
     */
    public function convertOrder(mixed $order): array
    {
        $onestockOrder = [];
        $onestockOrder = $this->objectCopyService->copyFieldsetToTarget(
            'onestock_order_mapping',
            'to_onestock_order',
            $order,
            $onestockOrder
        );
        return $onestockOrder;
    }
}
