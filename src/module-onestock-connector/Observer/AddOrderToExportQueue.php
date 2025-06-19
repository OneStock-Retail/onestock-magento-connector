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
use Smile\Onestock\Api\Data\ConfigInterface;

/**
 * Observer to export order placed
 */
class AddOrderToExportQueue implements ObserverInterface
{
    public const TOPIC_NAME = 'async.smile.onestock.api.orderexportinterface.export.post';

    public function __construct(
        protected MassSchedule $asyncBulkPublisher,
        protected LoggerInterface $logger,
        protected ConfigInterface $config,
    ) {
    }

    /**
     * Add order to export queue.
     *
     * The queue will be processed asynchrously later
     * by route /V1/order/:orderId/onestock_export
     */
    public function execute(Observer $observer): void
    {
        $orderId = $observer->getOrder()->getId();

        /////Start old code
        // $mode = $this->config->getOrderExportMode();
        // $event = $observer->getEvent()->getName();
        // if ($mode != $event) {
        //     $this->logger->debug("Order " . $orderId . " not queued during " . $event . " in mode " . $mode);
        //     return;
        // }
        /////End old code

        /////Start new code (temporary fix for paypal)
        $mode = ["sales_order_invoice_pay", "paypal_checkout_success"];
        $event = $observer->getEvent()->getName();
        
        if (!in_array($event, $mode)) {
            $this->logger->debug("Order " . $orderId . " not queued during " . $event . " in mode " . $mode);
            return;
        }
        /////End new code (temporary fix for paypal)

        $this->logger->debug("Order " . $orderId . " added to queue");
        try {
            $this->asyncBulkPublisher->publishMass(
                self::TOPIC_NAME,
                [
                    [
                        intval($orderId),
                    ],
                ],
                null,
                '0'
            );
        } catch (BulkException $bulkException) {
            $this->logger->error($bulkException->getLogMessage());
        }
    }
}
