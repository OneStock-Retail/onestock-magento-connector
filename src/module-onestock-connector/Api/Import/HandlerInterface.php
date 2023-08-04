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

namespace Smile\Onestock\Api\Import;

use Magento\Framework\DataObject;

/**
 * Represent One Task during Import
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
interface HandlerInterface
{
    /**
     * Check if import is possible
     *
     * @return array
     */
    public function validate(DataObject $res): bool;

    /**
     * Proceed to this step of the import
     *
     * @return array
     */
    public function process(DataObject $res): DataObject;
}
