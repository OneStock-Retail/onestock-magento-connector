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
use Magento\Framework\Filesystem\Driver\File as Dir;
use Magento\Framework\Filesystem\Io\File as Io;
use Smile\Onestock\Api\Handler\StockImportHandlerInterface;

/**
 * Handler to archive file
 */
class Archive implements StockImportHandlerInterface
{
    public function __construct(
        protected Io $filesystemIo,
        protected Dir $driverFile,
    ) {
    }
    /**
     * Always proceed
     */
    public function validate(DataObject $res): bool
    {
        return true;
    }
           
    /**
     * Move files to archive directory
     *
     * @throws FileSystemException
     */
    public function process(DataObject $res): DataObject
    {
        $this->driverFile->createDirectory($res['folder'] . "/archive/unified_stock");
        foreach ($res['files'] as $file) {
            $this->filesystemIo->mv($file, str_replace('/in/unified_stock', '/archive/unified_stock', $file));
        }
        
        return $res;
    }
}
