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

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use RuntimeException;
use Smile\Onestock\Helper\CacheToken;
use Smile\Onestock\Service\Onestock\Orders as OrdersApi;

/**
 * Service to get order from onestock
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
class Orders
{
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(
        protected OrdersApi $orders,
        protected CacheToken $tokenHelper,
    ) {
    }

    /**
     * Return order from onestock
     *
     * @return array
     * @throws Exception
     * @throws RuntimeException
     * @throws GuzzleException
     */
    public function get(int $orderId): array
    {
        return $this->tokenHelper->call(function ($config, $token) use ($orderId): array {
            return $this->orders->get($config, $token, $orderId);
        });
    }

    /**
     * Post new order to onestock
     *
     * @return array
     * @throws Exception
     * @throws RuntimeException
     * @throws GuzzleException
     */
    public function post(mixed $onestockOrder): array
    {
        return $this->tokenHelper->call(function ($config, $token) use ($onestockOrder) {
            return $this->orders->post($config, $token, $onestockOrder);
        });
    }
}
