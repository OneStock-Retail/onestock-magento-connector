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

namespace Smile\OnestockDeliveryPromise\Model\Request;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Magento\Framework\Webapi\ServiceInputProcessor;
use Magento\Framework\Webapi\ServiceOutputProcessor;
use Psr\Http\Message\ResponseInterface;
use Smile\Onestock\Api\Data\Authentication\TokenInterface;
use Smile\Onestock\Api\Data\ConfigInterface;
use Smile\OnestockDeliveryPromise\Api\Data\PromiseInterface;
use Smile\OnestockDeliveryPromise\Helper\Config;
use Smile\OnestockDeliveryPromise\Model\Data\Promise as PromiseDataModel;

/**
 * Rest request to query orders
 */
class Promise
{
    public function __construct(
        protected Client $httpClient,
        protected ServiceInputProcessor $toClassProcessor,
        protected ServiceOutputProcessor $toArrayProcessor,
        protected Config $config,
    ) {
    }

    /**
     * Use guzzle to send a request
     */
    public function send(Request $request, ConfigInterface $server): ResponseInterface
    {
        return $this->httpClient->send($request, $server->getOptions());
    }

    /**
     * Retrive promise from onestock
     *
     * @param array<int, array<string, mixed>> $items
     * @param string[] $methods
     * @return PromiseInterface[]
     */
    public function get(
        ConfigInterface $server,
        TokenInterface $token,
        array $items,
        array $methods,
        string $country,
        string $postcode
    ): array {
        try {
            $request = new Request(
                'GET',
                $server->getHost() . '/delivery_promises',
                [
                    'Content-Type' => 'application/json',
                ],
                json_encode(
                    $this->toArrayProcessor->convertValue(
                        // @phpstan-ignore-next-line
                        $token,
                        TokenInterface::class
                    ) + [
                        "sales_channel" => $server->getSalesChannel(),
                    ] + [
                        "line_items" => $items,
                    ]
                      + [
                        "delivery_options" => array_map(
                            function ($method) use ($country, $postcode) {
                                return [
                                "delivery_method" => $method,
                                "destination" => [
                                        "location" => [
                                            "country" => $country,
                                            "postcode" => $postcode,
                                        ],
                                    ],
                                ];
                            },
                            $methods
                        ),
                    ]
                )
            );
            $response = $this->send($request, $server);
            if ($response->getStatusCode() != 200) {
                throw new RequestException(
                    $response->getBody()->getContents(),
                    $request,
                    $response
                );
            }
            $jsonContent = json_decode($response->getBody()->getContents(), true);

            if (!isset($jsonContent['delivery_options'])) {
                throw new RequestException(
                    $response->getBody()->getContents(),
                    $request,
                    $response
                );
            }

            if ($this->config->greenEnabled()) {
                usort($jsonContent["delivery_options"], function ($l, $r) {
                    return $l["carbon_footprint"] <=> $r["carbon_footprint"];
                });
                $jsonContent["delivery_options"][0]["green_option"] = true;
            }

            return array_map(function ($promise) {
                    return $this->toClassProcessor->convertValue(
                        [
                        'data' => $promise,
                        ],
                        PromiseDataModel::class
                    );
            }, $jsonContent['delivery_options']);
        } catch (RequestException $e) {
            throw new Exception(
                $e->getResponse() ? $e->getResponse()->getBody()->getContents() : '',
                $e->getCode(),
                $e
            );
        }
    }
}
