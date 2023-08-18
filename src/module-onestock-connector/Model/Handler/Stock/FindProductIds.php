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

/**
 * Class
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
class FindProductIds implements StockImportHandlerInterface
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
     * Enrichiment of product entity_id from sku in file
     *
     * @return array
     */
    public function process(DataObject $res): DataObject
    {
        $tableName = $this->connection->getTableName($res['table']);
        $query = $this->connection->select()->from(false, ['entity_id' => 'f.entity_id'])->joinInner(
            ['f' => $this->connection->getTableName('catalog_product_entity')],
            'p.item_id = f.sku',
            []
        );

        $this->connection->query(
            $this->connection->updateFromSelect($query, ['p' => $tableName])
        );
        return $res;
    }
}
