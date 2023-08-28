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

namespace Smile\Onestock\Cron;

use Magento\Framework\DataObject;
use Smile\Onestock\Api\Handler\StockImportHandlerInterface;

/**
 * Stock import class to be used by virtual type
 */
class Import
{
    /**
     * @var StockImportHandlerInterface[] $handlers
     */
    protected array $handlers;

    /**
     * Constructor.
     *
     * @param StockImportHandlerInterface[] $data
     */
    public function __construct(
        array $data = []
    ) {
        $this->handlers = $data;
    }

    /**
     * Load available imports
     */
    public function start(): void
    {
        $res = new DataObject();
        foreach ($this->handlers as $handler) {
            if (!$handler->validate($res)) {
                break;
            }
            $res = $handler->process($res);
        }
    }
}
