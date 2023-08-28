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

namespace Smile\Onestock\Block\Adminhtml\System\Config\Field;

use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;
use Magento\Shipping\Model\Config\Source\Allmethods;

/**
 * Block to generate select within shipping method
 */
class Method extends Select
{
    /**
     * @param Context $context 
     * @param Allmethods $carrierMethods 
     * @param \ArrayObject[] $data 
     * @return void 
     */
    public function __construct(
        Context $context,
        private Allmethods $carrierMethods,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Set "name" for <select> element
     */
    public function setInputName(string $value): mixed
    {
        return $this->setName($value);
    }

    /**
     * Set "id" for <select> element
     *
     * @return $this
     */
    public function setInputId(string $value)
    {
        return $this->setId($value);
    }

    /**
     * Render block HTML
     */
    public function _toHtml(): string
    {
        if (!$this->getOptions()) {
            $this->setOptions($this->carrierMethods->toOptionArray());
        }
        return parent::_toHtml();
    }
}
