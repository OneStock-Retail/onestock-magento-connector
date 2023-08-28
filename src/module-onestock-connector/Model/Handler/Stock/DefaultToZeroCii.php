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
 * Handler to default all stock to 0 when a full stock is requested
 */
class DefaultToZeroCii implements StockImportHandlerInterface
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
     *
     * @return bool
     */
    public function validate(DataObject $res): bool
    {
        return true;
    }

    /**
     * After import set 0/out of stock product unspecified in the file
     *
     * @return DataObject
     * @throws Zend_Db_Select_Exception
     */
    public function process(DataObject $res): DataObject
    {

        $tableName = $this->connection->getTableName($res['table']);
        $stockTable = $this->connection->getTableName('cataloginventory_stock_item');
        $query = $this->connection->select()
        ->from(false, ['qty' => new Zend_Db_Expr('0'), 'is_in_stock' => new Zend_Db_Expr('0')])
        ->joinInner(
            ['e' => $this->connection->getTableName('catalog_product_entity')],
            'p.product_id = e.entity_id',
            []
        )
        ->joinLeft(
            ['f' => $tableName],
            'p.product_id = f.entity_id',
            []
        )
        ->where('f.entity_id is NULL and type_id = "simple"');

        $this->connection->query(
            $this->connection->updateFromSelect($query, ['p' => $stockTable])
        );

        $statusTable = $this->connection->getTableName('cataloginventory_stock_status');
        $query->reset(Select::COLUMNS)->columns(
            [
                'qty' => new Zend_Db_Expr('0'),
                'stock_status' => new Zend_Db_Expr('0'),
            ]
        );
        $this->connection->query(
            $this->connection->updateFromSelect($query, ['p' => $statusTable])
        );
        return $res;
    }
}
