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
use Smile\Onestock\Api\Handler\StockImportHandlerInterface;

/**
 * Handler to retreive all product ids involved by current pipeline
 */
class FindDiffIds implements StockImportHandlerInterface
{
    /**
     * Skip manual reindex if too much product
     */
    public const THRESHOLD = 5000;

    /**
     * This variable contains a ResourceConnection
     */
    protected AdapterInterface $connection;

    public function __construct(
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
     * Prepare list of product to reindex (do nothing above 5k product limit)
     *
     * @return DataObject
     * @throws Exception
     */
    public function process(DataObject $res): DataObject
    {
        $query = $this->connection->select()
            ->from($this->connection->getTableName($res['table']), ['entity_id', 'item_id'])
            ->where("entity_id is not NULL");
        $ids = $this->connection->fetchPairs($query);

        if (count($ids) < self::THRESHOLD) {
            $res['ids'] = array_keys($ids);
            $res['skus'] = array_values($ids);
        }
        return $res;
    }
}
