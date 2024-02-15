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
    public const CONFIG_FTP_ENABLED = 'smile_onestock/ftp/remote_enabled';
    public const CONFIG_FTP_USERNAME = 'smile_onestock/ftp/remote_username';
    public const CONFIG_FTP_PASSWORD = 'smile_onestock/ftp/remote_password';
    public const CONFIG_FTP_PATH = 'smile_onestock/ftp/remote_path';
    public const CONFIG_FTP_HOST = 'smile_onestock/ftp/remote_host';
    public const CONFIG_FTP_CLEANUP = 'smile_onestock/ftp/remote_cleanup';
    public const TABLE = 'onestock_transition_unified_stock';
    public const USE_MODULE_ = 'smile_onestock/stock/use_';

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
            'regex' => '/' . str_replace('*', '.*', $pattern) . '/',
            'folder' => $folder,
            'use_msi' => $this->useMsi(),
            'use_legacy' => $this->useLegacy(),
            'table' => self::TABLE,
            'ftp' => $this->getSftpCredentials(),
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
     * Return ftp credential if feature is enabled
     */
    public function getSftpCredentials(): array
    {
        $credential = [];
        $configEnabled = (bool) $this->scopeConfig->getValue(
            self::CONFIG_FTP_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
        if ($configEnabled) {
            $credential['username'] = $this->scopeConfig->getValue(
                self::CONFIG_FTP_USERNAME,
                ScopeInterface::SCOPE_STORE
            );
            $credential['password'] = $this->scopeConfig->getValue(
                self::CONFIG_FTP_PASSWORD,
                ScopeInterface::SCOPE_STORE
            );
            $credential['path'] = $this->scopeConfig->getValue(
                self::CONFIG_FTP_PATH,
                ScopeInterface::SCOPE_STORE
            );
            $credential['host'] = $this->scopeConfig->getValue(
                self::CONFIG_FTP_HOST,
                ScopeInterface::SCOPE_STORE
            );
            $credential['cleanup'] = $this->scopeConfig->getValue(
                self::CONFIG_FTP_CLEANUP,
                ScopeInterface::SCOPE_STORE
            );
        }
        return $credential;
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
