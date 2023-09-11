<?php

declare(strict_types=1);

namespace Smile\Onestock\Plugin;

use Magento\Sales\Model\Order\Shipment\TrackFactory;

/**
 * Add tracking link to shipment being created
 */
class AddTrackingLink
{
    public function __construct(
        protected TrackFactory $trackFactory
    ) {
    }

    /**
     * Add tracking link
     */
    public function aroundUpdate(
        mixed $subject,
        mixed $proceed,
        mixed $order,
        mixed $onestockOrder,
        mixed $parcel
    ): mixed {
        $shipment = $proceed($order, $onestockOrder, $parcel);
        if (isset($parcel['shipment']['tracking_code'])) {
            $shipment->addTrack(
                $this->trackFactory->create()->addData(
                    [
                        'number' => $parcel['shipment']['tracking_code'],
                        'title' => $parcel['shipment']['tracking_link'],
                        'carrier_code' => $parcel['shipment']['transporter'],
                    ]
                )
            );
        }
        return $shipment;
    }
}
