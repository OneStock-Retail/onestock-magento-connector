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
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Smile\Onestock\Cron\RetryOrderExport;

/**
 * Test order publication in queue
 *
 * @author   Pascal Noisette <paschandlersal.noisette@smile.fr>
 */
class RetryTest extends TestCase
{
    /**
     * Object to test
     */
    protected RetryOrderExport $cron;

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
            $this->cron = ObjectManager::getInstance()->create("Smile\Onestock\Cron\RetryOrderExport");
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage(), 0, $e);
        }
    }

    /**
     * Trigger a publication
     *
     * @throw \GuzzleHttp\Exception\RequestException
     */
    public function testMapping(): void
    {
        $this->cron->start();
    }
}
