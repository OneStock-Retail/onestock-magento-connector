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
use Psr\Log\LoggerInterface;

/**
 * Consumer for order placed waiting export
 */
class ShipmentImportConsumer
{
    /**
     * @param SerializerInterface $serializer
     * @return void
     */
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    /**
     * Process order placed
     *
     * @param string $operation
     * @throws \Exception
     */
    public function process(string $orderId): void
    {
        try {
            $this->logger->debug("consumer process :" . $orderId);
        } catch (Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }
}
