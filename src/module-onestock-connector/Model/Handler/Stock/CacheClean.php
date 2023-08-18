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

namespace Smile\Onestock\Model\Handler\Stock;

use Magento\Framework\App\Cache\Type\Block as Html;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Indexer\IndexerRegistry;
use Magento\PageCache\Model\Cache\Type as Fpc;
use Smile\Onestock\Api\Handler\StockImportHandlerInterface;

/**
 * Class
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
class CacheClean implements StockImportHandlerInterface
{
    /**
     * @param Manager $moduleManager
     * @param IndexerRegistry $indexerRegistry
     * @return void
     */
    public function __construct(
        protected CacheInterface $cache
    ) {
    }
    /**
     * Always proceed
     *
     * @return array
     */
    public function validate(DataObject $res): bool
    {
        return true;
    }
           
    /**
     * Clean FPC and Block cache
     *
     * @return array
     */
    public function process(DataObject $res): DataObject
    {
        $this->cache->clean([
            Html::CACHE_TAG,
            Fpc::CACHE_TAG,
        ]);
        
        return $res;
    }
}
