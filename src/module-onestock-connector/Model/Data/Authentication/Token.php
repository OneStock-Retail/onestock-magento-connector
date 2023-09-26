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
     * @inheritdoc
     */
    public function getToken(): string
    {
        return $this->getData('token');
    }

    /**
     * @inheritdoc
     */
    public function setToken(string $token)
    {
        return $this->setData('token', $token);
    }

    /**
     * @inheritdoc
     */
    public function getSiteId(): string
    {
        return $this->getData('site_id');
    }

    /**
     * @inheritdoc
     */
    public function setSiteId(string $siteId)
    {
        return $this->setData('site_id', $siteId);
    }
}
