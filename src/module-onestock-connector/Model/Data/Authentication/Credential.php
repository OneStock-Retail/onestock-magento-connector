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
use Smile\Onestock\Api\Data\Authentication\CredentialInterface;

/**
 * Data model used in login input
 */
class Credential extends DataObject implements CredentialInterface
{
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

    /**
     * @inheritdoc
     */
    public function getUserId(): string
    {
        return $this->getData('user_id');
    }

    /**
     * @inheritdoc
     */
    public function setUserId(string $userId)
    {
        return $this->setData('user_id', $userId);
    }

    /**
     * @inheritdoc
     */
    public function getPassword(): string
    {
        return $this->getData('password');
    }

    /**
     * @inheritdoc
     */
    public function setPassword(string $password)
    {
        return $this->setData('password', $password);
    }
}
