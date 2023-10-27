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
use Psr\Log\LoggerInterface;
use Smile\Onestock\Helper\CacheToken;
use Smile\Onestock\Helper\Mapping;
use Smile\OnestockDeliveryPromise\Api\Data\PromiseInterface;
use Smile\OnestockDeliveryPromise\Api\ProductInterface;
use Smile\OnestockDeliveryPromise\Model\Request\Promise as Request;
use Smile\OnestockDeliveryPromise\Helper\Config;

/**
 * Service implementing the interface to get promise
 */
class Promise implements ProductInterface
{

    public function __construct(
        protected LoggerInterface $logger,
        protected Request $request,
        protected CacheToken $tokenHelper,
        protected Mapping $mapping,
        protected Config $config,
    ) {
    }

    /**
     * Implement product web service
     *
     * @return PromiseInterface[]
     */
    public function getPromiseForSku(string $sku): array
    {
        return $this->get(
            [
                [
                    "item_id" => $sku,
                    "qty" => 1,
                ]
            ],
            array_keys($this->config->getMethods()),
            $this->config->getGuestCountry()
        );
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
            $res = $this->tokenHelper->call(function ($config, $token) use ($items, $methods, $country): array {
                return $this->request->get($config, $token, $items, $methods, $country);
            });
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return $res;
    }
}
