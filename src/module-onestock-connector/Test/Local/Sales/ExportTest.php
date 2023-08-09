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
use Magento\Framework\App\Bootstrap;
use Magento\Framework\App\Http;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;
use Magento\Framework\Event\Observer;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Smile\Onestock\Observer\ExportOrder;

/**
 * Test order publication in queue
 *
 * @author   Pascal Noisette <paschandlersal.noisette@smile.fr>
 */
class ExportTest extends TestCase
{
    /**
     * Object to test
     */
    protected ExportOrder $observer;

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
            $this->observer = ObjectManager::getInstance()->create("Smile\Onestock\Observer\ExportOrder");
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage(), 0, $e);
        }
    }

    /**
     * Trigger a publication
     *
     * @throw \GuzzleHttp\Exception\RequestException
     */
    public function testPipeline(): void
    {
        $this->observer->execute(new Observer(["order" => new DataObject(["id" => 1])]));
    }
}
