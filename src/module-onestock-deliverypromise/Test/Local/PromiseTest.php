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

namespace Smile\Onestock\Test\Local;

use Exception;
use Magento\Framework\App\Bootstrap;
use Magento\Framework\App\Http;
use Magento\Framework\App\ObjectManager;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Smile\OnestockDeliveryPromise\Service\Promise;

/**
 * Test promise
 */
class PromiseTest extends TestCase
{
    /**
     * Object to test
     */
    protected Promise $service;

    /**
     * Instanciate object to test
     *
     * @throws RuntimeException
     */
    public function setUp(): void
    {
        parent::setUp();

        try {
            Bootstrap::create(BP, $_SERVER)->createApplication(Http::class);
            $this->service = ObjectManager::getInstance()->create('Smile\OnestockDeliveryPromise\Service\Promise');
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage(), 0, $e);
        }
    }

    /**
     * Launch magento promise
     *
     * @throw \GuzzleHttp\Exception\RequestException
     */
    public function testPromise(): void
    {
        $items = [["item_id" => "WSH01-29-Green", "qty" => 1]];
        $country = "FR";
        $methods = ["flatrate_flatrate"];
        $res = $this->service->get($items, $methods, $country);
        var_dump($res);
    }
}
