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

/**
 * Service to get order from onestock
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
class Orders
{
    /**
     * Return order from onestock
     *
     * @return array
     */
    public function get(): array
    {
        return json_decode(file_get_contents(__DIR__ . '/../Test/Stub/order.json'), true);
    }
}
