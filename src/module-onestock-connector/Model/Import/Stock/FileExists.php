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

use Magento\Framework\DataObject;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem\Driver\File;
use Psr\Log\LoggerInterface;
use Smile\Onestock\Api\Import\HandlerInterface;

/**
 * Class
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
class FileExists implements HandlerInterface
{
    public function __construct(
        protected File $driverFile,
        protected LoggerInterface $logger
    ) {
    }
    
    /**
     * Check if there is a file to process
     *
     * @return array
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
     * @return array
     * @throws FileSystemException
     */
    public function process(DataObject $res): DataObject
    {
        $this->driverFile->createDirectory($res['folder'] . "/in/unified_stock");
        $paths = $this->driverFile->readDirectory($res['folder'] . "/in/unified_stock");
        $paths = preg_grep('/' . str_replace('*', '.*', $res['pattern']) . '/', $paths);

        $res->setData('files', $paths);
        return $res;
    }
}
