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

namespace Smile\Onestock\Api\Data\Authentication;

/**
 * Data model use in login input
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
interface CredentialInterface
{
    /**
     * Gets site_id
     *
     * @return string
     */
    public function getSiteId();

    /**
     * Sets site_id
     *
     * @param string $site_id Your Site ID, as provided by your OneStock contact
     * @return $this
     */
    public function setSiteId(string $site_id);

    /**
     * Gets user_id
     *
     * @return string
     */
    public function getUserId();

    /**
     * Sets user_id
     *
     * @param string $user_id The username provided by your OneStock contact
     * @return $this
     */
    public function setUserId(string $user_id);

    /**
     * Gets password
     *
     * @return string
     */
    public function getPassword();

    /**
     * Sets password
     *
     * @param string $password The password associated to the username you are trying to get a token with
     * @return $this
     */
    public function setPassword(string $password);
}
