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

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\App\Config as CacheConfig;
use RuntimeException;
use Smile\Onestock\Helper\Config as OnestockConfig;
use Smile\Onestock\Model\Data\Authentication\Credential;
use Smile\Onestock\Model\Data\Authentication\Token;
use Smile\Onestock\Model\Request\Authentication as AuthenticationApi;

/**
 * Add cache to api token authentification
 */
class CacheToken
{
    public const TOKEN_CACHE_IDENTIFIER = "token_cache_identifier";

    public const TOKEN_LIFETIME = 86400;

    public function __construct(
        protected OnestockConfig $config,
        protected AuthenticationApi $authentication,
        protected CacheInterface $cache
    ) {
    }

    /**
     * Wrapper to call login if an auth error is catched
     *
     * @param mixed $proceed function
     * @throws Exception
     * @throws RuntimeException
     * @throws GuzzleException
     */
    public function call(mixed $proceed): mixed
    {
        $credential = new Credential($this->config->getCredentials());
        try {
            $cached = $this->cache->load(self::TOKEN_CACHE_IDENTIFIER);
            if ($cached) {
                $token = new Token([
                    "site_id" => $credential->getSiteId(),
                    "token" => $cached,
                ]);
                return $proceed($this->config, $token);
            }
        } catch (Exception $e) {
            if ($e->getCode() != 401) {
                throw $e;
            }
            $this->cache->remove(self::TOKEN_CACHE_IDENTIFIER);
        }

        $token = $this->authentication->login($this->config, $credential);
        $this->cache->save(
            $token->getToken(),
            self::TOKEN_CACHE_IDENTIFIER,
            [CacheConfig::CACHE_TAG],
            self::TOKEN_LIFETIME
        );
        return $proceed($this->config, $token);
    }
}
