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

 namespace Smile\OnestockDeliveryPromise\Api;

 use Smile\OnestockDeliveryPromise\Api\Data\PromiseInterface;

/**
 * Interface for order export service
 */
interface ProductInterface
{
    /**
     * Get promise for product
     *
     * @param int $orderId
     * @return PromiseInterface[]
     */
    public function getPromiseForSku(string $sku): array;
}
