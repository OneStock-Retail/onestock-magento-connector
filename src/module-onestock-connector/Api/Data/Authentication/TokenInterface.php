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
 * Data model use in login ouput
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
interface TokenInterface
{
    /**
     * Gets token
     *
     * @return string
     */
    public function getToken();

    /**
     * Sets token
     *
     * @param string $token Your temporary authentication
     * @return $this
     */
    public function setToken(string $token);
}
