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

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DataObject;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Module\Manager;
use Magento\Store\Model\ScopeInterface;
use Smile\Onestock\Api\Handler\StockImportHandlerInterface;

/**
 * Handler to init store config for later use in pipeline
 */
class Configure implements StockImportHandlerInterface
{
    public const CONFIG_FOLDER = 'smile_onestock/ftp/folder';

    public const CONFIG_PATTERN = 'smile_onestock/ftp/us_full_pattern';

    public const TABLE = 'onestock_transition_unified_stock';

    public const USE_MODULE_ = 'smile_onestock/stock/use_';

    /**
     * This variable contains a ResourceConnection
     */
    protected AdapterInterface $connection;

    public function __construct(
        protected ScopeConfigInterface $scopeConfig,
        protected ResourceConnection $resourceConnection,
        protected Manager $moduleManager,
        protected string $configPattern = self::CONFIG_PATTERN
    ) {
        $this->connection = $resourceConnection->getConnection();
    }

    /**
     * Always proceed
     */
    public function validate(DataObject $res): bool
    {
        return true;
    }

    /**
     * Initialise which filename must be processed in which table
     */
    public function process(DataObject $res): DataObject
    {
        $folder = $this->scopeConfig->getValue(
            self::CONFIG_FOLDER,
            ScopeInterface::SCOPE_STORE
        );
        $pattern = $this->scopeConfig->getValue(
            $this->configPattern,
            ScopeInterface::SCOPE_STORE
        );
        return new DataObject([
            'pattern' => $pattern,
            'folder' => $folder,
            'use_msi' => $this->useMsi(),
            'use_legacy' => $this->useLegacy(),
            'table' => self::TABLE,
        ]);
    }

    /**
     * Predicate msi is enabled in config
     */
    public function useMsi(): bool
    {
        $configEnabled = (bool) $this->scopeConfig->getValue(
            self::USE_MODULE_ . 'msi',
            ScopeInterface::SCOPE_STORE
        );
        $moduleEnabled = $this->moduleManager->isEnabled('Magento_Inventory');
        $structureOk = $this->connection->isTableExists('inventory_source_item');
        return $configEnabled && $moduleEnabled && $structureOk;
    }

    /**
     * Predicate legacy is enabled in config
     */
    public function useLegacy(): bool
    {
        $configEnabled = (bool) $this->scopeConfig->getValue(
            self::USE_MODULE_ . 'legacy',
            ScopeInterface::SCOPE_STORE
        );
        $moduleEnabled = $this->moduleManager->isEnabled('Magento_CatalogInventory');
        $structureOk = $this->connection->isTableExists('cataloginventory_stock_item');
        return $configEnabled && $moduleEnabled && $structureOk;
    }
}
