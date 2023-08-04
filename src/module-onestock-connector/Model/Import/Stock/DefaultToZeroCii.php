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

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DataObject;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Smile\Onestock\Api\Import\HandlerInterface;
use Zend_Db_Expr;
use Zend_Db_Select_Exception;

/**
 * Class
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
class DefaultToZeroCii implements HandlerInterface
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
     * @return array
     */
    public function validate(DataObject $res): bool
    {
        return true;
    }

    /**
     * After import set 0/out of stock product unspecified in the file
     *
     * @return array
     * @throws Zend_Db_Select_Exception
     */
    public function process(DataObject $res): DataObject
    {

        $tableName = $this->connection->getTableName($res['table']);
        $stockTable = $this->connection->getTableName('cataloginventory_stock_item');
        $query = $this->connection->select()
        ->from(false, ['qty' => new Zend_Db_Expr(0), 'is_in_stock' => new Zend_Db_Expr(0)])
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
        return $res;
    }
}
