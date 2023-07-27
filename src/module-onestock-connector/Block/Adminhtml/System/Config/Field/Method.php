<?php

namespace Smile\Onestock\Block\Adminhtml\System\Config\Field;

use Magento\Framework\View\Element\Html\Select;
use Magento\Shipping\Model\Config\Source\Allmethods;
use Magento\Backend\Block\Template\Context;

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
    )
    {
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
     * @param $value
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