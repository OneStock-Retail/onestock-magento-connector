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

namespace Smile\Onestock\Service;

use Exception;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\App\Config as CacheConfig;
use Smile\Onestock\Model\Authentication\Credential;
use Smile\Onestock\Model\Authentication\Token;
use Smile\Onestock\Model\Config as OnestockConfig;
use Smile\Onestock\Service\Onestock\Authentication as AuthenticationApi;
use Smile\Onestock\Service\Onestock\Orders as OrdersApi;

/**
 * Service to get order from onestock
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
class Orders
{
    public const TOKEN_CACHE_IDENTIFIER = "token_cache_identifier";

    public const TOKEN_LIFETIME = 86400;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(
        protected OnestockConfig $config,
        protected AuthenticationApi $authentication,
        protected OrdersApi $orders,
        protected CacheInterface $cache
    ) {
    }

    /**
     * Return order from onestock
     *
     * @return array
     */
    public function get(int $orderId): array
    {
        $credential = new Credential($this->config->getCredentials());
        try {
            $cached = $this->cache->load(self::TOKEN_CACHE_IDENTIFIER);
            if ($cached) {
                $token = new Token([
                    "site_id" => $credential->getSiteId(),
                    "token" => $cached,
                ]);
                return $this->orders->get($this->config, $token, $orderId);
            }
        } catch (Exception $e) {
            if ($e->getCode() != 401) {
                throw $e;
            }
        }

        $token = $this->authentication->login($this->config, $credential);
        $this->cache->save(
            $token->getToken(),
            self::TOKEN_CACHE_IDENTIFIER,
            [CacheConfig::CACHE_TAG],
            self::TOKEN_LIFETIME
        );
        return $this->orders->get($this->config, $token, $orderId);
    }
}
