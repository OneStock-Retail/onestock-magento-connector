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

use \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use \Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\BlockInterface;

/**
 * Block to configure ruleset within a nice UI selector
 * @see https://developer.adobe.com/commerce/php/tutorials/admin/create-dynamic-row-configuration/
 * @author   Pascal Noisette <pascal.noisette@smile.fr>
 */
class RulesetMap extends AbstractFieldArray
{
    
    /**
     *
     * @var BlockInterface
     */
    private $optionRenderer;
    
    /**
     * Return selector of shipping method
     *
     * @return BlockInterface
     * @throws LocalizedException
     */
    private function getOptionRenderer()
    {
        
        if (!$this->optionRenderer) {
            $this->optionRenderer = $this->getLayout()->createBlock(
                Method::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->optionRenderer;
    }
    
    /**
     * Build two column since ruleset consist of method and ruleset code.
     *
     * @return void
     * @throws LocalizedException
     */
    protected function _prepareToRender()
    {
        $this->addColumn(
            'method',
            ['label' => __('Shipping method'), 'class' => 'required-entry',  'renderer' => $this->getOptionRenderer()]
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
     *
     * @param DataObject $row
     * @return void
     */
    protected function _prepareArrayRow(DataObject $row)
    {
        $selected = $row->getRuleset();
        $options = [];
        if ($selected) {
            $options['option_' . $this->getOptionRenderer()->calcOptionHash($selected)]
            = 'selected="selected"';
        }
        $row->setData('option_extra_attrs', $options);
    }
}
