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
 * Handler at the beginning of the pipeline to read csv data
 */
class ReadFile implements StockImportHandlerInterface
{
    public const THRESHOLD = 5000;
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
     * Store parsed csv data into temporary table
     */
    public function process(DataObject $res): DataObject
    {
        $i = 0;
        $data = [];
        $tableName = $this->connection->getTableName($res['table']);

        foreach ($res['files'] as $file) {
            $fh = fopen($file, 'r');
            $header = fgetcsv($fh);
            while ($rowData = fgetcsv($fh)) {
                $i++;
                if ($i > self::THRESHOLD) {
                    $this->connection->insertOnDuplicate($tableName, $data);
                    $data = [];
                    $i = 0;
                }
                $data[] = array_combine($header, $rowData);
            }
            fclose($fh);
        }
        $this->connection->insertOnDuplicate($tableName, $data);
        return $res;
    }
}
