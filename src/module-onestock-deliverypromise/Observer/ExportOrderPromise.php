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

namespace Smile\OnestockDeliveryPromise\Observer;

use Exception;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

/**
 * Append promise to mapping
 */
class ExportOrderPromise implements ObserverInterface
{
    public function __construct(
        protected LoggerInterface $logger,
    ) {
    }

    /**
     * Add order to export queue
     */
    public function execute(Observer $observer): void
    {
        $promise = [];
        $target = $observer->getTarget();
        $address = $observer->getSource()->getShippingAddress();

        if (!$address || !$address->getOnestockDp()) {
            $this->logger->error(__('No promise for order %1', $address->getParentId()));
            return;
        }

        try {
            $promise = json_decode($address->getOnestockDp(), true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            return;
        }

        if (!$promise || !isset($promise['delivery_method'])) {
            $this->logger->error(__('No promise for order %1', $address->getParentId()));
            return;
        }

        $target['delivery_promise'] =  [
            'original_delivery_option' => $promise,
            'sent_delivery_option' => $promise,
        ];
    }
}
