<?php
namespace Smile\Onestock\Block\Adminhtml\System\Config\Field;

use \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use \Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\BlockInterface;

class RulesetMap extends AbstractFieldArray
{
    
    /**
     * 
     * @var BlockInterface
     */
    private $optionRenderer;
    
    /**
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
     * 
     * @return void 
     * @throws LocalizedException 
     */
    protected function _prepareToRender()
    {
        $this->addColumn('method', ['label' => __('Shipping method'), 'class' => 'required-entry',  'renderer' => $this->getOptionRenderer()]);
        $this->addColumn('ruleset', ['label' => __('Ruleset'), 'class' => 'required-entry']);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add rule');
    }
    
    
    /**
    * Prepare existing row data object
    * @see https://developer.adobe.com/commerce/php/tutorials/admin/create-dynamic-row-configuration/
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