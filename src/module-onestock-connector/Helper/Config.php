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

namespace Smile\Onestock\Helper;

use GuzzleHttp\RequestOptions;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Store\Model\ScopeInterface;
use Smile\Onestock\Api\Data\ConfigInterface;

/**
 * Central config
 */
class Config implements ConfigInterface
{
    public const CONFIG_TIMEOUT = 'smile_onestock/api/timeout';
    public const CONFIG_HOST = 'smile_onestock/api/host';
    public const CONFIG_SALES_CHANNEL = 'smile_onestock/general/sales_channel';
    public const USER_ID = 'smile_onestock/api/user_id';
    public const PASSWORD = 'smile_onestock/api/password';
    public const SITE_ID = 'smile_onestock/general/site_id';
    public const ORDER_RETRY_COUNT = 'smile_onestock/api/order_retry_count';
    public const LOG_ENABLED = 'smile_onestock/api/log_enabled';
    public const FIELDS = 'smile_onestock/api/fields';
    public const LOGIN_CACHE_LIFETIME = 'smile_onestock/api/login_cache_lifetime';
    public const ORDER_EXPORT_MODE = 'smile_onestock/api/order_export_mode';

    public function __construct(
        protected ScopeConfigInterface $scopeConfig,
        protected EncryptorInterface $encryptor
    ) {
    }

    /**
     * @inheritdoc
     */
    public function getHost(): string
    {
        return $this->scopeConfig->getValue(self::CONFIG_HOST, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @inheritdoc
     */
    public function getSalesChannel(): string
    {
        return $this->scopeConfig->getValue(self::CONFIG_SALES_CHANNEL, ScopeInterface::SCOPE_STORE) ?? "";
    }

    /**
     * @inheritdoc
     */
    public function getOptions(): array
    {
        $timeout = $this->scopeConfig->getValue(
            self::CONFIG_TIMEOUT,
            ScopeInterface::SCOPE_STORE
        );
        $options = [];
        $options[RequestOptions::CONNECT_TIMEOUT] = $timeout;
        $options[RequestOptions::TIMEOUT] = $timeout;
        $options[RequestOptions::READ_TIMEOUT] = $timeout;
        return $options;
    }

    /**
     * @inheritdoc
     */
    public function getCredentials(): array
    {
        return [
            'user_id' => $this->scopeConfig->getValue(
                self::USER_ID,
                ScopeInterface::SCOPE_STORE
            ) ?? "",
            'password' => $this->encryptor->decrypt(
                trim(
                    $this->scopeConfig->getValue(
                        self::PASSWORD,
                        ScopeInterface::SCOPE_STORE
                    ) ?? ""
                )
            ),
            'site_id' => $this->scopeConfig->getValue(
                self::SITE_ID,
                ScopeInterface::SCOPE_STORE
            ) ?? "",
        ];
    }

    /**
     * @inheritdoc
     */
    public function getFields(): array
    {
        $fields = $this->scopeConfig->getValue(
            self::FIELDS,
            ScopeInterface::SCOPE_STORE
        );

        return explode(',', $fields);
    }

    /**
     * @inheritdoc
     */
    public function getOrderRetryCount(): string
    {
        return $this->scopeConfig->getValue(self::ORDER_RETRY_COUNT, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Return when export should occur
     */
    public function getOrderExportMode(): string
    {
        return $this->scopeConfig->getValue(self::ORDER_EXPORT_MODE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @inheritdoc
     */
    public function getLoginCacheLifetime(): int
    {
        $lifetime = $this->scopeConfig->getValue(
            self::LOGIN_CACHE_LIFETIME,
            ScopeInterface::SCOPE_STORE
        );
        return intval($lifetime);
    }

    /**
     * @inheritdoc
     */
    public function logIsEnabled(): bool
    {
        $enabled = $this->scopeConfig->getValue(
            self::LOG_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
        return (bool) $enabled;
    }
}
