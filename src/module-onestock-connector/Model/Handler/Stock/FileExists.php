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
use Smile\Onestock\Api\Handler\StockImportHandlerInterface;

/**
 * Handler to init filename for later use in the pipeline
 */
class FileExists implements StockImportHandlerInterface
{
    public function __construct(protected File $driverFile)
    {
    }
    
    /**
     * Check if there is a file to process
     */
    public function validate(DataObject $res): bool
    {
        /** @var DataObject $res */
        $res = $this->process($res);
        $paths = $res->getData('files');
        return count($paths) > 0;
    }

    /**
     * Return the file to be processed
     *
     * @throws FileSystemException
     */
    public function process(DataObject $res): DataObject
    {
        $this->driverFile->createDirectory($res['folder'] . '/in/unified_stock');
        $paths = $this->driverFile->readDirectory($res['folder'] . '/in/unified_stock');

        $paths = preg_grep($res['regex'], $paths);

        $res->setData('files', $paths);
        return $res;
    }
}
