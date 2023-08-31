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
    public function getHost();

    /**
     * Connexion option
     *
     * @return string[]
     */
    public function getOptions();

    /**
     * Connexion option
     *
     * @return string[]
     */
    public function getCredentials();

    /**
     * Mandatory fields
     *
     * @return string[]
     */
    public function getFields();
}
