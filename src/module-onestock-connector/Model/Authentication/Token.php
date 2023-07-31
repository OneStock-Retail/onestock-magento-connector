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

namespace Smile\Onestock\Model\Authentication;

use Magento\Framework\DataObject;
use Smile\Onestock\Api\Data\Authentication\TokenInterface;

/**
 * Data model use in login ouput
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
class Token extends DataObject implements TokenInterface
{
    /**
     * Gets token
     */
    public function getToken(): string
    {
        return $this->getData("token");
    }

    /**
     * Sets token
     *
     * @param string $token Your temporary authentication
     * @return $this
     */
    public function setToken(string $token)
    {
        return $this->setData("token", $token);
    }
}
