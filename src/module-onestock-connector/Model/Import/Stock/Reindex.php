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

namespace Smile\Onestock\Model\Import\Stock;

use Exception;
use Magento\CatalogInventory\Model\Indexer\Stock\Processor as StockProcessor;
use Magento\Framework\DataObject;
use Magento\Framework\Indexer\IndexerRegistry;
use Magento\Framework\Module\Manager;
use Magento\InventoryIndexer\Indexer\InventoryIndexer;
use Smile\Onestock\Api\Import\HandlerInterface;

/**
 * Class
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
class Reindex implements HandlerInterface
{
    /**
     * @return void
     */
    public function __construct(
        protected Manager $moduleManager,
        protected IndexerRegistry $indexerRegistry,
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
     * Launch reindex after import full
     *
     * @return array
     * @throws Exception
     */
    public function process(DataObject $res): DataObject
    {
        $indexName = StockProcessor::INDEXER_ID;
        if ($this->moduleManager->isEnabled('Magento_Inventory')) {
            $indexName = InventoryIndexer::INDEXER_ID;
        }
        $index = $this->indexerRegistry->get($indexName);
        if (!$index->isScheduled()) {
            $index->reindexAll();
        }
        
        return $res;
    }
}
