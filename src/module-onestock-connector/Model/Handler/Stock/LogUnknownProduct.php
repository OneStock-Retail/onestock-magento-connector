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
use Psr\Log\LoggerInterface;
use Smile\Onestock\Api\Handler\StockImportHandlerInterface;
use Zend_Db_Select_Exception;
use Zend_Db_Statement_Exception;

/**
 * Handler to add messages to log
 */
class LogUnknownProduct implements StockImportHandlerInterface
{
    /**
     * This variable contains a ResourceConnection
     */
    protected AdapterInterface $connection;

    /**
     * Constructor
     */
    public function __construct(
        protected LoggerInterface $logger,
        ResourceConnection $connection,
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
     * Write a warning to log file for missing product
     *
     * @throws Zend_Db_Select_Exception
     * @throws Zend_Db_Statement_Exception
     */
    public function process(DataObject $res): DataObject
    {
        $tableName = $this->connection->getTableName($res['table']);
        $query = $this->connection->select()
            ->from($tableName, ['item_id' => 'item_id'])
            ->where('entity_id is NULL');
        
        $statement = $this->connection->query($query);
        while ($row = $statement->fetch()) {
            $this->logger->info(__('Product not found in magento database: "%1"', $row['item_id']));
        }
        return $res;
    }
}
