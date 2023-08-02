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

 namespace Smile\Onestock\Model\Queue;

use Exception;
use Magento\Framework\MessageQueue\PublisherInterface;
use Psr\Log\LoggerInterface;

/**
 * Enqueue order placed
 */
class OrderExportPublisher
{
    public const TOPIC_NAME = 'smile.onestock.order.export';

    /**
     * @param SerializerInterface $serializer
     * @return void
     */
    public function __construct(
        private LoggerInterface $logger,
        private PublisherInterface $publisher
    ) {
    }

    /**
     * Enequeue one order
     *
     * @throws \Exception
     */
    public function process(string $orderId): void
    {
        try {
            $this->publisher->publish(self::TOPIC_NAME, $orderId);
        } catch (Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }
}
