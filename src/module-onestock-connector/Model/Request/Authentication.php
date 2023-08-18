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

namespace Smile\Onestock\Model\Request;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Magento\Framework\Webapi\ServiceInputProcessor;
use Magento\Framework\Webapi\ServiceOutputProcessor;
use RuntimeException;
use Smile\Onestock\Api\Data\Authentication\CredentialInterface;
use Smile\Onestock\Api\Data\Authentication\TokenInterface;
use Smile\Onestock\Api\Data\ConfigInterface;
use Smile\Onestock\Model\Data\Authentication\Token;

/**
 * Service to login
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
class Authentication
{
    /**
     * Converter from object to array
     */
    protected ServiceInputProcessor $toClassProcessor;

    /**
     * Converter from array to object
     */
    protected ServiceOutputProcessor $toArrayProcessor;

    /**
     * Guzzle http client
     */
    protected Client $httpClient;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(
        Client $httpClient,
        ServiceInputProcessor $toClassProcessor,
        ServiceOutputProcessor $toArrayProcessor
    ) {
        $this->httpClient = $httpClient;
        $this->toClassProcessor = $toClassProcessor;
        $this->toArrayProcessor = $toArrayProcessor;
    }

    /**
     * Operation login
     *
     * @param ConfigInterface $server dynamic url
     * @param CredentialInterface $credential dynamic credentials
     * @return TokenInterface session bearer
     * @throws RuntimeException
     * @throws Exception
     * @throws GuzzleException
     */
    public function login(ConfigInterface $server, CredentialInterface $credential): TokenInterface
    {
        try {
            $request = new Request(
                'POST',
                $server->getHost() . '/login',
                [
                    'Content-Type' => 'application/json',
                ],
                json_encode(
                    $this->toArrayProcessor->convertValue(
                        $credential,
                        CredentialInterface::class
                    )
                )
            );
            $response = $this->httpClient->send($request, $server->getOptions());
            if ($response->getStatusCode() != 200) {
                throw new RequestException(
                    $response->getBody()->getContents(),
                    $request,
                    $response
                );
            }
            return $this->toClassProcessor->convertValue(
                ['site_id' => $credential->getSiteId()] +
                json_decode($response->getBody()->getContents(), true),
                Token::class
            );
        } catch (RequestException $e) {
            throw new Exception(
                $e->getResponse() ? $e->getResponse()->getBody()->getContents() : '',
                $e->getCode(),
                $e
            );
        } catch (Exception $e) {
            throw $e;
        }
    }
}
