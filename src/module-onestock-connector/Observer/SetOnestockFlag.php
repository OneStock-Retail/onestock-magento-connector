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

namespace Smile\Onestock\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Observer to flag order
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
class SetOnestockFlag implements ObserverInterface
{
    public const NOT_EXPORTED = 1;

    /**
     * Flag order
     */
    public function execute(Observer $observer): void
    {
        $observer->getOrder()->setOnestockExported(self::NOT_EXPORTED);
    }
}
