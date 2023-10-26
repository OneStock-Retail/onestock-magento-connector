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

namespace Smile\OnestockDeliveryPromise\Service;

use Exception;
use Magento\Sales\Api\OrderRepositoryInterface;
use Smile\OnestockDeliveryPromise\Api\Data\PromiseInterface;
use Psr\Log\LoggerInterface;
use Smile\Onestock\Helper\CacheToken;
use Smile\Onestock\Helper\Mapping;
use Smile\OnestockDeliveryPromise\Model\Request\Promise as Request;

/**
 * Service implementing the interface to get promise
 */
class Promise
{
    public const EXPORTED = 2;

    public const ERROR = 4;

    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
        protected LoggerInterface $logger,
        protected Request $request,
        protected CacheToken $tokenHelper,
        protected Mapping $mapping
    ) {
    }

    /**
     * Retrieve promise for quote
     *
     * @return PromiseInterface[]
     */
    public function get(array $items, array $methods, string $country): array
    {
        $res = [];
        try {
            /** @var PromiseInterface[] $res */
            $res = $this->tokenHelper->call(function ($config, $token) use ($items, $methods, $country) : array {
                return $this->request->get($config, $token, $items, $methods, $country);
            });
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return $res;
    }
}
