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

namespace Smile\OnestockDeliveryPromise\Model\Data;

use Magento\Framework\DataObject;
use Smile\OnestockDeliveryPromise\Api\Data\PromiseInterface;

/**
 * Represent Server Configuration
 */
class Promise extends DataObject implements PromiseInterface
{
    /**
     * Get Shipping method
     */
    public function getDeliveryMethod(): string
    {
        return $this->getData("delivery_method");
    }

    /**
     * Get country
     */
    public function getCountry(): string
    {
        return $this->getData("destination")['location']['country'];
    }

    /**
     * Get Status
     */
    public function getStatus(): string
    {
        return $this->getData("status");
    }

    /**
     * Get validity date of current promise
     */
    public function getCutoff(): int
    {
        return $this->getData("cut_off");
    }

    /**
     * Get estimated earliest date of arrival
     */
    public function getEtaStart(): int
    {
        return $this->getData("eta_start");
    }

    /**
     * Get estimated date of arrival
     */
    public function getEtaEnd(): int
    {
        return $this->getData("eta_end");
    }

    /**
     * Get package count
     */
    public function getShipmentNumber(): int
    {
        return $this->getData("shipment_number");
    }

    /**
     * Count Parcel
     */
    public function getCost(): float
    {
        return $this->getData("cost");
    }

    /**
     * Get Cost
     */
    public function getCarbonFootprint(): int
    {
        return $this->getData("carbon_footprint");
    }
}
