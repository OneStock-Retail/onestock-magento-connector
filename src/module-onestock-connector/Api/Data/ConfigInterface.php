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

namespace Smile\Onestock\Api\Data;

/**
 * Represent Server Configuration
 */
interface ConfigInterface
{
    /**
     * Return server url
     *
     * @return string
     */
    public function getHost(): string;

    /**
     * Return sales channel
     *
     * @return string
     */
    public function getSalesChannel(): string;

    /**
     * Connexion option
     *
     * @return string[]
     */
    public function getOptions(): array;

    /**
     * Connexion option
     *
     * @return string[]
     */
    public function getCredentials(): array;

    /**
     * Mandatory fields
     *
     * @return string[]
     */
    public function getFields(): array;

    /**
     * Return max value
     */
    public function getOrderRetryCount(): string;

    /**
     * Return when export should occur
     *
     * @return string
     */
    public function getOrderExportMode(): string;

    /**
     * Return lifetime
     */
    public function getLoginCacheLifetime(): int;

    /**
     * Predicate log is enabled in config
     */
    public function logIsEnabled(): bool;
}
