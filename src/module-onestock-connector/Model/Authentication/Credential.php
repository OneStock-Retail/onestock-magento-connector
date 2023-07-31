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
use Smile\Onestock\Api\Data\Authentication\CredentialInterface;

/**
 * Data model use in login input
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
class Credential extends DataObject implements CredentialInterface
{
    /**
     * Gets site_id
     */
    public function getSiteId(): string
    {
        return $this->getData("site_id");
    }

    /**
     * Sets site_id
     *
     * @param string $site_id Your Site ID, as provided by your OneStock contact
     * @return $this
     */
    public function setSiteId(string $site_id)
    {
        return $this->setData("site_id", $site_id);
    }

    /**
     * Gets user_id
     */
    public function getUserId(): string
    {
        return $this->getData("user_id");
    }

    /**
     * Sets user_id
     *
     * @param string $user_id The username provided by your OneStock contact
     * @return $this
     */
    public function setUserId(string $user_id)
    {
        return $this->setData("user_id", $user_id);
    }

    /**
     * Gets password
     */
    public function getPassword(): string
    {
        return $this->getData("password");
    }

    /**
     * Sets password
     *
     * @param string $password The password associated to the username you are trying to get a token with
     * @return $this
     */
    public function setPassword(string $password)
    {
        return $this->setData("password", $password);
    }
}
