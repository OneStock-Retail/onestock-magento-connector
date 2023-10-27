<?php

/**
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @author    Pascal Noisette <Pascal.Noisette@smile.fr>
 * @copyright 2023 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

declare(strict_types=1);

namespace Smile\OnestockDeliveryPromise\Block\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\Product;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Smile\OnestockDeliveryPromise\Helper\Config;

/**
 * Block rendering promise for a given product.
 *
 * @author   Pascal Noisette <Pascal.Noisette@smile.fr>
 */
class Promise extends Template implements IdentityInterface
{
    protected Registry $coreRegistry;

    /**
     * Availability constructor.
     *
     * @param Context                    $context                   Application context
     * @param ProductRepositoryInterface $productRepository         Product Repository
     * @param array                      $data                      Block Data
     */
    public function __construct(
        Context $context,
        protected ProductRepositoryInterface $productRepository,
        protected Config $config,
        array $data = []
    ) {
        $this->coreRegistry = $context->getRegistry();

        parent::__construct(
            $context,
            $data
        );
    }

    /**
     * Retreive product for x-init data
     */
    public function getJsLayout(): string
    {
        $jsLayout = $this->jsLayout;

        $jsLayout['components']['catalog-product-promise']['sku'] = $this->getProduct()->getSku();
        $jsLayout['components']['catalog-product-promise']['methods'] = $this->config->getMethods();

        return json_encode($jsLayout);
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return string[]
     */
    public function getIdentities(): array
    {
        $identities = $this->getProduct()->getIdentities();
        return $identities;
    }

    /**
     * Retrieve current product model
     */
    protected function getProduct(): Product
    {
        if (!$this->coreRegistry->registry('product') && $this->getProductId()) {
            return $this->productRepository->getById($this->getProductId());
        }

        return $this->coreRegistry->registry('product');
    }
}
