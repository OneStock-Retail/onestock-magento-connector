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

 namespace Smile\Onestock\Api;

/**
 * Sales interface for order updates
 */
interface ShipmentImportInterface
{
    /**
     * Receive the Order Id that must be refreshed
     *
     * @param int $orderId
     * @return void
     */
    public function requestUpdate(
        int $orderId
    ): void;
}
