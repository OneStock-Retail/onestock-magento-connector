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

namespace Smile\Onestock\Test\Local\Sales;

use Exception;
use Magento\Framework\App\Area;
use Magento\Framework\App\Bootstrap;
use Magento\Framework\App\Http;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\State;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Smile\Onestock\Service\OrderUpdate;

/**
 * Test shipment import
 */
class ImportTest extends TestCase
{
    /**
     * Object to test
     */
    protected OrderUpdate $import;

    protected State $applicationState;

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
            $this->import = ObjectManager::getInstance()->create('Smile\Onestock\Service\OrderUpdate');
            $this->applicationState = ObjectManager::getInstance()->get(State::class);
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage(), 0, $e);
        }
    }

    /**
     * Get an order to onestock to update magento order.
     *
     * @throw \GuzzleHttp\Exception\RequestException
     */
    public function testPipeline(): void
    {
        
        $output = null;
        $input = '000000005';
        $this->applicationState
            ->emulateAreaCode(
                Area::AREA_CRONTAB,
                [$this->import, 'requestUpdate'],
                [$input, 0, "", ""]
            );
    }
}
