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

namespace Smile\Onestock\Model;

use Magento\Framework\DataObject;

/**
 * Class Stock Import
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
class Import
{
    /**
     * @var array $handlers
     */
    protected array $handlers;

    /**
     * Constructor.
     *
     * @param array $data
     */
    public function __construct(
        array $data = []
    ) {
        $this->handlers = $data;
    }

    /**
     * Load available imports
     *
     * @param array $data
     * @throws \Exception
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
