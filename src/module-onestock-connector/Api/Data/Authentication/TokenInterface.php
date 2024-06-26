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
 * Output of login used as authenticating token for all api call towards onestock.
 */
interface TokenInterface
{
    /**
     * Gets token
     *
     * @return string
     */
    public function getToken(): string;

    /**
     * Sets token
     *
     * @param string $token
     * @return $this
     */
    public function setToken(string $token);

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
}
