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

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\BlockInterface;
use Magento\Framework\View\Element\Html\Select;

/**
 * Block to configure ruleset within a nice UI selector
 *
 * @see https://developer.adobe.com/commerce/php/tutorials/admin/create-dynamic-row-configuration/
 */
class RulesetMap extends AbstractFieldArray
{
    /**
     * Return selector of shipping method
     *
     * @throws LocalizedException
     */
    private function getOptionRenderer(): BlockInterface
    {
        
        return $this->getLayout()->createBlock(
            Method::class,
            '',
            ['data' => ['is_render_to_js_template' => true]]
        );
    }
    
    /**
     * Build two column since ruleset consist of method and ruleset code.
     *
     * @throws LocalizedException
     */
    protected function _prepareToRender(): void
    {
        $this->addColumn(
            'method',
            ['label' => __('Shipping method'), 'class' => 'required-entry', 'renderer' => $this->getOptionRenderer()]
        );
        $this->addColumn(
            'ruleset',
            ['label' => __('Ruleset'), 'class' => 'required-entry']
        );
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add rule');
    }
    
    /**
     * Reload existing value if any
     */
    protected function _prepareArrayRow(DataObject $row): void
    {
        $selected = $row->getRuleset();
        $options = [];
        if ($selected) {
            /** @var Select $renderer */
            $renderer = $this->getOptionRenderer();
            $options['option_' . $renderer->calcOptionHash($selected)]
            = 'selected="selected"';
        }
        $row->setData('option_extra_attrs', $options);
    }
}
