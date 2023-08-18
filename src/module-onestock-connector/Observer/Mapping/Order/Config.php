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

namespace Smile\Onestock\Observer\Mapping\Order;

use Exception;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Store\Model\ScopeInterface;
use Psr\Log\LoggerInterface;

/**
 * Export order to onestock
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
class Config implements ObserverInterface
{
    public const CONFIG_RULESET_MAP = "smile_onestock/general/rulesets_map";

    public const TYPE_FFS = "ffs";

    public const CONFIG_SALES_CHANNEL = "smile_onestock/general/sales_channel";

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(
        protected ScopeConfigInterface $scopeConfig,
        protected Json $serializer,
        protected LoggerInterface $logger,
    ) {
    }
    /**
     * Add order to export queue
     */
    public function execute(Observer $observer): void
    {
        $order = $observer->getSource();
        $onestockOrder = $observer->getTarget();
        
        try {
            $rulesets = $this->serializer->unserialize($this->scopeConfig->getValue(
                self::CONFIG_RULESET_MAP,
                ScopeInterface::SCOPE_STORE
            ));
            foreach ($rulesets as $ruleset) {
                if ($ruleset['method'] == $order->getShippingMethod()) {
                    $onestockOrder['original_ruleset_id'] = $ruleset['ruleset'];
                }
            }
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
        $onestockOrder['types'] = [self::TYPE_FFS] ;
        $onestockOrder['sales_channel'] = $this->scopeConfig->getValue(
            self::CONFIG_SALES_CHANNEL,
            ScopeInterface::SCOPE_STORE
        );
    }
}
