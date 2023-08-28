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
use Magento\Sales\Api\Data\OrderInterface;
use RuntimeException;
use Smile\Onestock\Api\Data\Authentication\CredentialInterface;
use Smile\Onestock\Api\Data\Authentication\TokenInterface;
use Smile\Onestock\Api\Data\Sales\OrderInterface as OnestockOrderInterface;
use Smile\Onestock\Model\Data\Sales\Order as OnestockOrderInstance;
use Smile\Onestock\Api\Data\ConfigInterface;

/**
 * Rest request to query orders
 */
class Orders
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
     * Retrive order from onestock
     *
     * @param ConfigInterface $server 
     * @param TokenInterface $token 
     * @param int $id 
     * @return OnestockOrderInterface
     * @throws RuntimeException 
     * @throws Exception 
     * @throws GuzzleException 
     */
    public function get(ConfigInterface $server, TokenInterface $token, int $id): OnestockOrderInterface
    {
        try {
            $request = new Request(
                'GET',
                $server->getHost() . '/v3/orders/' . $id,
                [
                    'Content-Type' => 'application/json',
                ],
                json_encode(
                    $this->toArrayProcessor->convertValue(
                        // @phpstan-ignore-next-line
                        $token,
                        TokenInterface::class
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
                [
                    "data"=>json_decode($response->getBody()->getContents(), true),
                ],
                OnestockOrderInstance::class
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

    /**
     * Create order
     *
     * @param ConfigInterface $server 
     * @param TokenInterface $token 
     * @param \ArrayObject[] $onestockOrder 
     * @return void 
     * @throws RuntimeException 
     * @throws Exception 
     * @throws GuzzleException 
     */
    public function post(ConfigInterface $server, TokenInterface $token, array $onestockOrder): void
    {
        try {
            $request = new Request(
                'POST',
                $server->getHost() . '/v3/orders',
                [
                    'Content-Type' => 'application/json',
                ],
                json_encode(
                    $this->toArrayProcessor->convertValue(
                        // @phpstan-ignore-next-line
                        $token,
                        TokenInterface::class
                    ) + ['order' => $onestockOrder]
                )
            );
            $response = $this->httpClient->send($request, $server->getOptions());
            if ($response->getStatusCode() != 201) {
                throw new RequestException(
                    $response->getBody()->getContents(),
                    $request,
                    $response
                );
            }
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
