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

use BadMethodCallException;
use LogicException;
use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Module\Manager;
use RuntimeException;

/**
 * Block to display if module is enabled
 *
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
class Msi extends Field
{
    /**
     * @param array $data
     * @return void
     * @throws RuntimeException
     * @throws LogicException
     * @throws BadMethodCallException
     */
    public function __construct(
        private Manager $moduleManager,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Retrieve the Magento_Inventory status
     */
    protected function _getElementHtml(AbstractElement $element): string
    {
        return $this->moduleManager->isEnabled('Magento_Inventory') ? __('Yes') : __('No');
    }
}
