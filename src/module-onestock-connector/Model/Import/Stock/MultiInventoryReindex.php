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
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DataObject;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Indexer\IndexerRegistry;
use Magento\Framework\Module\Manager;
use Magento\InventoryIndexer\Indexer\InventoryIndexer;
use Smile\Onestock\Api\Import\HandlerInterface;

/**
 * Class
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
class MultiInventoryReindex implements HandlerInterface
{
    /**
     * This variable contains a ResourceConnection
     */
    protected AdapterInterface $connection;
    /**
     * @return void
     */
    public function __construct(
        protected IndexerRegistry $indexerRegistry,
        protected Manager $moduleManager,
        ResourceConnection $connection,
    ) {
        $this->connection = $connection->getConnection();
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
        if (!$this->moduleManager->isEnabled('Magento_Inventory')) {
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