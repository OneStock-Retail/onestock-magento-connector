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

namespace Smile\Onestock\Observer;

use Magento\AsynchronousOperations\Model\MassSchedule;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\BulkException;
use Psr\Log\LoggerInterface;

/**
 * Observer to export order placed
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
class ExportOrder implements ObserverInterface
{
    public const TOPIC_NAME = 'async.smile.onestock.api.sales.orderexportinterface.export.post';

    /**
     * @param CustomerAddress $customerAddressHelper
     */
    public function __construct(
        protected MassSchedule $asyncBulkPublisher,
        protected LoggerInterface $logger,
    ) {
    }

    /**
     * Add order to export queue
     */
    public function execute(Observer $observer): void
    {
        $orderId = $observer->getOrder()->getId();
        try {
            $this->asyncBulkPublisher->publishMass(
                self::TOPIC_NAME,
                [
                    [
                        intval($orderId),
                    ],
                ]
            );
        } catch (BulkException $bulkException) {
            $this->logger->error($bulkException->getLogMessage());
        }
    }
}
