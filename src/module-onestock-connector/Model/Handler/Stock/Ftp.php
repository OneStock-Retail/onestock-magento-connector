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

use Magento\Framework\DataObject;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Filesystem\Io\Sftp;
use Psr\Log\LoggerInterface;
use Smile\Onestock\Api\Handler\StockImportHandlerInterface;

/**
 * Handler to init filename for later use in the pipeline
 */
class Ftp implements StockImportHandlerInterface
{
    public function __construct(
        protected File $driverFile,
        protected LoggerInterface $logger,
    ) {
    }
    
    /**
     * Check if there is a file to process
     */
    public function validate(DataObject $res): bool
    {
        return true;
    }

    /**
     * Fetch files over ftp if enabled
     *
     * @throws FileSystemException
     */
    public function process(DataObject $res): DataObject
    {
        if (!empty($res['ftp'])) {
            $this->driverFile->createDirectory($res['ftp']['path'] . '/in/unified_stock');
            $connection = new Sftp();
            $connection->open($res['ftp']);
            $connection->cd($res['ftp']['path']);
            foreach (array_keys($connection->rawls()) as $filename) {
                if (preg_match($res['regex'], $filename)) {
                    $connection->read($filename, $res['folder'] . '/in/unified_stock/' . $filename);
                    if ($res['ftp']['cleanup'] == "remove") {
                        $connection->rm($filename);
                    } elseif ($res['ftp']['cleanup'] == "archive") {
                        $connection->mkdir("/archives");
                        $connection->mv($filename, "archives/" . $filename);
                    }
                }
            }
        }
        return $res;
    }
}
