<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\Onestock
 * @author    Pascal Noisette <pascal.noisette@smile.fr>
 * @copyright 2023 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
namespace Smile\Onestock\Block\Adminhtml\System\Config\Field;
/**
 * Block to display if module is enabled
 *
 * @category Smile
 * @package  Smile\Onestock
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
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
