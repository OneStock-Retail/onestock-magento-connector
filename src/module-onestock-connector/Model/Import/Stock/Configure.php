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

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Store\Model\ScopeInterface;
use Smile\Onestock\Api\Import\HandlerInterface;

/**
 * Class
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
class Configure implements HandlerInterface
{
    public const CONFIG_FOLDER = 'smile_onestock/ftp/folder';

    public const CONFIG_PATTERN = 'smile_onestock/ftp/us_full_pattern';

    public const TABLE = 'onestock_transition_unified_stock';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(
        protected ScopeConfigInterface $scopeConfig
    ) {
    }

    /**
     * Always proceed
     *
     * @return array
     */
    public function validate(DataObject $res): bool
    {
        return true;
    }

    /**
     * Initialise which filename must be processed in which table
     *
     * @return array
     */
    public function process(DataObject $res): DataObject
    {
        $folder = $this->scopeConfig->getValue(
            self::CONFIG_FOLDER,
            ScopeInterface::SCOPE_STORE
        );
        $pattern = $this->scopeConfig->getValue(
            self::CONFIG_PATTERN,
            ScopeInterface::SCOPE_STORE
        );
        return new DataObject([
            "us_full_pattern" => $pattern,
            "folder" => $folder,
            "table" => self::TABLE,
        ]);
    }
}
