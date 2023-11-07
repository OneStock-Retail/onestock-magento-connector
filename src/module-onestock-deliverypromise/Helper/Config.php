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

namespace Smile\OnestockDeliveryPromise\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Shipping\Model\Config\Source\Allmethods;
use Magento\Store\Model\ScopeInterface;

/**
 * Central config
 */
class Config
{
    public const GUEST_POSTCODE = 'smile_onestock/dp/guest_postcode';
    public const GUEST_COUNTRY = 'smile_onestock/dp/guest_country';
    public const BASE_CURRENCY = 'currency/options/base';
    public const ENABLED = 'smile_onestock/dp/dp_enabled';

    public function __construct(
        protected ScopeConfigInterface $scopeConfig,
        protected Allmethods $carrierMethods,
    ) {
    }

    /**
     * Retrieve enabled method codes
     *
     * @return string[]
     */
    public function getMethods(): array
    {
        $onlyActive = true;
        $carriers = $this->carrierMethods->toOptionArray($onlyActive);
        array_shift($carriers);
        $methods = array_reduce(
            $carriers,
            function ($res, $carrier) {
                foreach ($carrier['value'] as $method) {
                    $res[$method['value']] = $method['label'];
                }
                return $res;
            },
            []
        );
        return $methods;
    }

    /**
     * @inheritdoc
     */
    public function getGuestCountry(): string
    {
        return $this->scopeConfig->getValue(
            self::GUEST_COUNTRY,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Return currency code iso string
     */
    public function getBaseCurrencyCode(): string
    {
        return $this->scopeConfig->getValue(
            self::BASE_CURRENCY,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @inheritdoc
     */
    public function getGuestPostcode(): string
    {
        return $this->scopeConfig->getValue(
            self::GUEST_POSTCODE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @inheritdoc
     */
    public function isEnabled(): bool
    {
        return (bool) $this->scopeConfig->getValue(
            self::ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }
}
