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

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DataObject;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use Smile\Onestock\Api\Handler\StockImportHandlerInterface;
use Zend_Db_Expr;

/**
 * Legacy inventory import
 */
class CatalogInventoryImport implements StockImportHandlerInterface
{
    protected AdapterInterface $connection;

    public function __construct(ResourceConnection $connection)
    {
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
     * Import file into legacy inventory
     */
    public function process(DataObject $res): DataObject
    {
        if (!$res['use_legacy']) {
            return $res;
        }

        $tableName = $this->connection->getTableName($res['table']);
        $mainColumns = [
            'product_id' => 'entity_id',
            'qty' => 'quantity',
        ];
        $isInStock = new Zend_Db_Expr('IF(quantity>0, 1, 0)');
        $extraColumns = [
            'stock_id' => 'stock_id' ,
            'website_id' => 'website_id',
        ];

        $select = $this->connection->select();
        $select->from(['main_table' => $tableName])
               ->reset(Select::COLUMNS)
               ->columns($mainColumns + ['is_in_stock' => $isInStock])
               ->joinCross(
                   ['f' => $this->connection->getTableName('cataloginventory_stock')],
                   $extraColumns
               )
               ->where('entity_id is not NULL');

        $this->connection->query(
            $select->insertFromSelect(
                $this->connection->getTableName('cataloginventory_stock_item'),
                array_keys($mainColumns + ['is_in_stock' => $isInStock] + $extraColumns),
                (bool) AdapterInterface::INSERT_ON_DUPLICATE
            )
        );

        $this->connection->query(
            $select->insertFromSelect(
                $this->connection->getTableName('cataloginventory_stock_status'),
                array_keys($mainColumns + ['stock_status' => $isInStock] + $extraColumns),
                (bool) AdapterInterface::INSERT_ON_DUPLICATE
            )
        );

        return $res;
    }
}
