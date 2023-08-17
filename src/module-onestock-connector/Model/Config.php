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

namespace Smile\Onestock\Model;

use GuzzleHttp\RequestOptions;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Smile\Onestock\Api\Data\ConfigInterface;

/**
 * Central config
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
class Config implements ConfigInterface
{
    public const CONFIG_TIMEOUT = 'smile_onestock/api/timeout';

    public const CONFIG_HOST = 'smile_onestock/api/host';

    public const USER_ID = 'smile_onestock/api/user_id';

    public const PASSWORD = 'smile_onestock/api/password';

    public const SITE_ID = 'smile_onestock/general/site_id';

    public const ORDER_RETRY_COUNT = 'smile_onestock/api/order_retry_count';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(
        protected ScopeConfigInterface $scopeConfig
    ) {
    }

    /**
     * Return server url
     *
     * @param mixed $method
     * @param mixed $resourcePath
     * @param mixed $body
     * @throws InvalidArgumentException
     */
    public function getHost(): string
    {
        $host = $this->scopeConfig->getValue(
            self::CONFIG_HOST,
            ScopeInterface::SCOPE_STORE
        );
        return $host;
    }

    /**
     * Connexion option
     *
     * @return array
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
     * Connexion option
     *
     * @return array
     */
    public function getCredentials(): array
    {
        return [
            "user_id" => $this->scopeConfig->getValue(
                self::USER_ID,
                ScopeInterface::SCOPE_STORE
            ),
            "password" => $this->scopeConfig->getValue(
                self::PASSWORD,
                ScopeInterface::SCOPE_STORE
            ),
            "site_id" => $this->scopeConfig->getValue(
                self::SITE_ID,
                ScopeInterface::SCOPE_STORE
            ),
        ];
    }

    /**
     * Return max value
     *
     * @param mixed $method
     * @param mixed $resourcePath
     * @param mixed $body
     * @throws InvalidArgumentException
     */
    public function getOrderRetryCount(): string
    {
        $max = $this->scopeConfig->getValue(
            self::ORDER_RETRY_COUNT,
            ScopeInterface::SCOPE_STORE
        );
        return $max;
    }
}
