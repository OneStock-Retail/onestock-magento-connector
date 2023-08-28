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

use Exception;
use Magento\CatalogInventory\Model\Indexer\Stock\Processor as StockProcessor;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DataObject;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Indexer\IndexerRegistry;
use Smile\Onestock\Api\Handler\StockImportHandlerInterface;

/**
 * Legacy inventory index when a set of product is changed
 */
class CatalogInventoryReindexIds implements StockImportHandlerInterface
{
    /**
     * This variable contains a ResourceConnection
     */
    protected AdapterInterface $connection;

    public function __construct(
        protected IndexerRegistry $indexerRegistry,
        protected StockProcessor $stockProcessor,
        ResourceConnection $connection,
    ) {
        $this->connection = $connection->getConnection();
    }

    /**
     * Always proceed
     *
     * @return bool
     */
    public function validate(DataObject $res): bool
    {
        return true;
    }

    /**
     * Launch reindex after diff
     *
     * @return DataObject
     * @throws Exception
     */
    public function process(DataObject $res): DataObject
    {
        $index = $this->indexerRegistry->get(StockProcessor::INDEXER_ID);
        if (!$index->isScheduled() && isset($res['ids'])) {
            $this->stockProcessor->reindexList($res['ids']);
        }

        return $res;
    }
}
