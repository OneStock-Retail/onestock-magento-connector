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

use Magento\Framework\Module\Manager;
use Magento\Config\Model\Config\Source\Yesno;
use Magento\Backend\Block\Template\Context;
use RuntimeException;
use LogicException;
use BadMethodCallException;

/**
 * Block to display if module is enabled
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
class Msi extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    private $moduleManager;

    /**
     *
     * @param Manager $moduleManager
     * @param Yesno $yesno
     * @param Context $context
     * @param array $data
     * @return void
     * @throws RuntimeException
     * @throws LogicException
     * @throws BadMethodCallException
     */
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
