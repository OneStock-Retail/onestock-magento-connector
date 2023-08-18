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
use Magento\Sales\Api\OrderRepositoryInterface;
use Psr\Log\LoggerInterface;
use Smile\Onestock\Api\OrderExportInterface;
use Smile\Onestock\Helper\CacheToken;
use Smile\Onestock\Helper\Mapping;
use Smile\Onestock\Model\Request\Orders as OrdersApi;

/**
 * Export order to onestock
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
class OrderExport implements OrderExportInterface
{
    public const EXPORTED = 2;

    public const ERROR = 4;

    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
        protected LoggerInterface $logger,
        protected OrdersApi $service,
        protected CacheToken $tokenHelper,
        protected Mapping $mapping
    ) {
    }

    /**
     * Export Order to Onestock
     */
    public function export(
        int $orderId
    ): void {
        $order = $this->orderRepository->get($orderId);
        try {
            $this->tokenHelper->call(function ($config, $token) use ($order): void {
                $this->service->post($config, $token, $this->mapping->convertOrder($order));
            });
            $order->setOnestockExported(self::EXPORTED);
        } catch (Exception $e) {
            $order->setOnestockExported(self::ERROR);
            $order->setOrderRetryCount($order->getOrderRetryCount() + 1);
        }
        $order->save();
    }
}
