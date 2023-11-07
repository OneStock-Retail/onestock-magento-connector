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
        if (!$this->hasData("destination") || !isset($this->getData("destination")['location'])) {
            return "";
        }
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
        return $this->hasData("cut_off") ? $this->getData("cut_off") : 0;
    }

    /**
     * Get estimated earliest date of arrival
     */
    public function getEtaStart(): int
    {
        return $this->hasData("eta_start") ? $this->getData("eta_start") : 0;
    }

    /**
     * Get estimated date of arrival
     */
    public function getEtaEnd(): int
    {
        return $this->hasData("eta_end") ? $this->getData("eta_end") : 0;
    }

    /**
     * Get package count
     */
    public function getShipmentNumber(): int
    {
        return $this->hasData("shipment_number") ? $this->getData("shipment_number") : 0;
    }

    /**
     * Count Parcel
     */
    public function getCost(): float
    {
        return $this->hasData("cost") ? $this->getData("cost") : 0;
    }

    /**
     * Get Cost
     */
    public function getCarbonFootprint(): int
    {
        return $this->hasData("carbon_footprint") ? $this->getData("carbon_footprint") : 0;
    }

    /**
     * Convert object data into string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
