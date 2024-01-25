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
 * Interface for order update service
 */
interface OrderUpdateInterface
{
    /**
     * Receive the Order Increment Id that must be refreshed
     *
     * @param string $order_id
     * @param int $date
     * @param string $old_state
     * @param string $new_state
     * @return void
     */
    public function requestUpdate(string $order_id, int $date, string $old_state, string $new_state): void;
}
