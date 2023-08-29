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
 * Clean up table created by any previous import
 */
class DropTable implements StockImportHandlerInterface
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
     * Cleanup from previous import
     */
    public function process(DataObject $res): DataObject
    {
        /** @var string $tableName */
        $tableName = $this->connection->getTableName($res['table']);

        $this->connection->resetDdlCache($tableName);
        $this->connection->dropTable($tableName);

        return $res;
    }
}
