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
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DataObject;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Indexer\IndexerRegistry;
use Magento\Framework\Module\Manager;
use Magento\InventoryIndexer\Indexer\InventoryIndexer;
use Smile\Onestock\Api\Handler\StockImportHandlerInterface;

/**
 * Handler to reindex stock
 */
class MultiInventoryReindex implements StockImportHandlerInterface
{
    /**
     * This variable contains a ResourceConnection
     */
    protected AdapterInterface $connection;

    public function __construct(
        protected IndexerRegistry $indexerRegistry,
        protected Manager $moduleManager,
        ResourceConnection $connection,
    ) {
        $this->connection = $connection->getConnection();
    }

    /**
     * Always proceed
     */
    public function validate(DataObject $res): bool
    {
        return true;
    }

    /**
     * Launch reindex after import full
     *
     * @throws Exception
     */
    public function process(DataObject $res): DataObject
    {
        if (!$res['use_msi']) {
            return $res;
        }

        $index = $this->indexerRegistry->get(InventoryIndexer::INDEXER_ID);
        if ($index->isScheduled()) {
            return $res;
        }

        $index->reindexAll();

        return $res;
    }
}
