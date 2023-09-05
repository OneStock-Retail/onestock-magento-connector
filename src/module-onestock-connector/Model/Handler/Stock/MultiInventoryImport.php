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
use Zend_Db_Select_Exception;

/**
 * Handler to import stock into inventory table
 */
class MultiInventoryImport implements StockImportHandlerInterface
{
        /**
         * This variable contains a ResourceConnection
         */
    protected AdapterInterface $connection;

    /**
     * Constructor
     */
    public function __construct(
        ResourceConnection $connection
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
     * Import file into magento source inventory
     *
     * @throws Zend_Db_Select_Exception
     */
    public function process(DataObject $res): DataObject
    {
        if (!$res['use_msi']) {
            return $res;
        }

        $tableName = $this->connection->getTableName($res['table']);
        $inventory = $this->connection->getTableName('inventory_source');
        $mainColumns = [
            'sku' => 'item_id',
            'quantity' => 'quantity',
            'status' => new Zend_Db_Expr('IF(quantity>0, 1, 0)'),
        ];
        $extraColumns = [
            'source_code' => 'source_code',
        ];

        $select = $this->connection->select();
        $select->from(['main_table' => $tableName])
               ->reset(Select::COLUMNS)
               ->columns($mainColumns)
               ->joinCross(
                   ['f' => $inventory],
                   $extraColumns
               )
               ->where('entity_id is not NULL');

        $this->connection->query(
            $select->insertFromSelect(
                $this->connection->getTableName('inventory_source_item'),
                array_keys($mainColumns + $extraColumns),
                (bool) AdapterInterface::INSERT_ON_DUPLICATE
            )
        );

        return $res;
    }
}
