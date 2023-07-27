<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade to newer
 * versions in the future.
 *
 * @package   Smile\Onestock
 * @author    Pascal Noisette <pascal.noisette@smile.fr>
 * @copyright 2023 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
namespace Smile\Onestock\Block\Adminhtml\System\Config\Field;

use Magento\Framework\View\Element\Html\Select;
use Magento\Shipping\Model\Config\Source\Allmethods;
use Magento\Framework\View\Element\Context;

/**
 * Block to generate select within shipping method
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
class Method extends Select
{
    
    /**
     * @var Allmethods
     */
    private $carrierMethods;

    /**
     *
     * @param Context $context
     * @param Allmethods $carrierMethods
     * @param array $data
     * @return void
     */
    public function __construct(
        Context $context,
        Allmethods $carrierMethods,
        array $data = []
    ) {
        $this->carrierMethods = $carrierMethods;
        return parent::__construct($context, $data);
    }

    /**
     * Set "name" for <select> element
     *
     * @param string $value
     * @return $this
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }

    /**
     * Set "id" for <select> element
     *
     * @param string $value
     * @return $this
     */
    public function setInputId($value)
    {
        return $this->setId($value);
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    public function _toHtml(): string
    {
        if (!$this->getOptions()) {
            $this->setOptions($this->carrierMethods->toOptionArray());
        }
        return parent::_toHtml();
    }
}
