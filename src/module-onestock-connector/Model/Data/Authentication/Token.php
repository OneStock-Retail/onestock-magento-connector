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

namespace Smile\Onestock\Model\Data\Authentication;

use Magento\Framework\DataObject;
use Smile\Onestock\Api\Data\Authentication\TokenInterface;

/**
 * Output of login used as authenticating token for all api call towards onestock.
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
}
