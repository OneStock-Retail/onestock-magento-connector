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
 * Data model used in login input
 */
interface CredentialInterface
{
    /**
     * Gets site id
     *
     * @return string
     */
    public function getSiteId(): string;

    /**
     * Sets site id
     *
     * @param string $siteId
     * @return $this
     */
    public function setSiteId(string $siteId);

    /**
     * Gets user id
     *
     * @return string
     */
    public function getUserId(): string;

    /**
     * Sets user id
     *
     * @param string $userId
     * @return $this
     */
    public function setUserId(string $userId);

    /**
     * Gets password
     *
     * @return string
     */
    public function getPassword(): string;

    /**
     * Sets password
     *
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password);
}
