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

namespace Smile\Onestock\Cron;

use Magento\AsynchronousOperations\Model\MassSchedule;
use Magento\Framework\Exception\BulkException;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactoryInterface;
use Psr\Log\LoggerInterface;
use Smile\Onestock\Api\Data\ConfigInterface;
use Smile\Onestock\Observer\AddOrderToExportQueue as RegularPublisher;
use Smile\Onestock\Service\OrderExport;

/**
 * Try to export order again when it failed
 */
class RetryOrderExport
{
    public function __construct(
        protected MassSchedule $asyncBulkPublisher,
        protected LoggerInterface $logger,
        protected CollectionFactoryInterface $orderCollectionFactory,
        protected ConfigInterface $config
    ) {
    }

    /**
     * Add order to export queue
     */
    public function start(): void
    {
        $orderCollection = $this->orderCollectionFactory->create();
        $orderCollection->addFieldToFilter('onestock_retries', ['lteq' => $this->config->getOrderRetryCount()]);
        $orderCollection->addFieldToFilter('onestock_export_status', strval(OrderExport::ERROR));
        try {
            $this->asyncBulkPublisher->publishMass(
                RegularPublisher::TOPIC_NAME,
                array_map('intval', $orderCollection->getAllIds()),
            );
        } catch (BulkException $bulkException) {
            $this->logger->error($bulkException->getLogMessage());
        }
    }
}
