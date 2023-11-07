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

namespace Smile\OnestockDeliveryPromise\Api\Data;

/**
 * Represent Server Configuration
 */
interface PromiseInterface
{
    /**
     * Get Shipping method
     *
     * @return string
     */
    public function getDeliveryMethod(): string;

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry(): string;

    /**
     * Get Status
     *
     * @return string
     */
    public function getStatus(): string;

    /**
     * Get validity date of current promise
     *
     * @return int
     */
    public function getCutoff(): int;

    /**
     * Get estimated earliest date of arrival
     *
     * @return int
     */
    public function getEtaStart(): int;

    /**
     * Get estimated date of arrival
     *
     * @return int
     */
    public function getEtaEnd(): int;

    /**
     * Count Parcel
     *
     * @return int
     */
    public function getShipmentNumber(): int;

    /**
     * Get Cost
     *
     * @return float
     */
    public function getCost(): float;

    /**
     * Get Carbon Footprint
     *
     * @return int
     */
    public function getCarbonFootprint(): int;

    /**
     * Cast to json string
     *
     * @return string
     */
    public function __toString(): string;
}
