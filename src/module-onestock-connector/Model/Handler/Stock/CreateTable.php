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
use Magento\Framework\DB\Ddl\Table;
use Smile\Onestock\Api\Handler\StockImportHandlerInterface;
use Zend_Db_Exception;

/**
 * Handler to create a temporary sql table
 */
class CreateTable implements StockImportHandlerInterface
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
     * Create temporary table
     *
     * @throws Zend_Db_Exception
     */
    public function process(DataObject $res): DataObject
    {

        /** @var string $tableName */
        $tableName = $this->connection->getTableName($res['table']);
        $table =  $this->connection->newTable($tableName)
        ->addColumn(
            'id',
            Table::TYPE_BIGINT,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'ID'
        )->addColumn(
            'entity_id',
            Table::TYPE_BIGINT,
            null,
            [],
            'Product ID'
        )->addColumn(
            'item_id',
            Table::TYPE_TEXT,
            255,
            [],
            'Sku'
        )->addColumn(
            'quantity',
            Table::TYPE_BIGINT,
            null,
            [],
            'Quantity'
        )->addIndex(
            $this->connection->getIndexName($tableName, ['entity_id']),
            ['entity_id']
        )->addIndex(
            $this->connection->getIndexName($tableName, ['item_id']),
            ['item_id']
        )->setComment(
            'Onestock temporary table for import'
        );
        $this->connection->createTable($table);

        return $res;
    }
}
