<?php

namespace Smile\Onestock\Block\Adminhtml\System\Config\Field;

class Msi extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    private $moduleManager;

    public function __construct(
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Config\Model\Config\Source\Yesno $yesno,
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->moduleManager      = $moduleManager;
    }


    /**
     * Retrieve the Magento_Inventory status
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * 
     * @return string
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return $this->moduleManager->isEnabled('Magento_Inventory') ? __('Yes') : __('No');
    }
}
