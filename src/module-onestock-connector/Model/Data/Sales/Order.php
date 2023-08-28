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

namespace Smile\Onestock\Model\Data\Sales;

use Magento\Framework\DataObject;
use Smile\Onestock\Api\Data\Sales\OrderInterface;

/**
 * Data model used to highlight usefull fields of onestock order
 */
class Order extends DataObject implements OrderInterface
{
}
