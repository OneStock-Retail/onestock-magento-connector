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
use Smile\Onestock\Api\Handler\StockImportHandlerInterface;
use Zend_Db_Expr;

/**
 * Handler to enrich temporary table with parent product
 */
class FindParents implements StockImportHandlerInterface
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
     * Restock parent configurable if stock on child
     */
    public function process(DataObject $res): DataObject
    {
        $tableName = $this->connection->getTableName($res['table']);
        $fields = [
            'entity_id' => 'f.parent_id',
            'item_id' => 'e.sku',
            'quantity' => new Zend_Db_Expr('SUM(p.quantity)'),
        ];

        /** @var \Magento\Framework\DB\Select $query */
        $query = $this->connection->select()->from(
            ['p' => $tableName],
            $fields
        )->joinInner(
            ['f' => $this->connection->getTableName('catalog_product_super_link')],
            'p.entity_id = f.product_id',
            []
        )->joinInner(
            ['e' => $this->connection->getTableName('catalog_product_entity')],
            'e.entity_id = f.parent_id',
            []
        )->group(
            'f.parent_id'
        )->having(
            'quantity>0'
        );

        $this->connection->query(
            $this->connection->insertFromSelect(
                $query,
                $tableName,
                array_keys($fields),
                AdapterInterface::INSERT_IGNORE
            )
        );
        return $res;
    }
}
