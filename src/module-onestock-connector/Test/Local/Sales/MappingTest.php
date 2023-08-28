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
use Magento\Sales\Api\OrderRepositoryInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Smile\Onestock\Helper\Mapping;

/**
 * Test the order mapping
 */
class MappingTest extends TestCase
{
    /**
     * Object to test
     */
    protected Mapping $mapping;

    /**
     * Loader
     */
    protected OrderRepositoryInterface $repository;

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
            $this->mapping = ObjectManager::getInstance()->create("Smile\Onestock\Helper\Mapping");
            $this->repository = ObjectManager::getInstance()->create("Magento\Sales\Api\OrderRepositoryInterface");
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage(), 0, $e);
        }
    }

    /**
     * Apply mapping and check resulting field
     */
    public function testMapping(): void
    {
        $input = $this->repository->get(1);
        $res = $this->mapping->convertOrder($input);
        $this->assertEquals($input->getIncrementId(), $res['id']);
    }
}
