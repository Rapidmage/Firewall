<?php
 
namespace Rapidmage\Firewall\Block\Adminhtml\Whiteip;
 
use Magento\Backend\Block\Widget\Grid as WidgetGrid;
 
class Grid extends WidgetGrid
{
   //public function _construct()
    //{
        //parent::_construct(); 
        //if ($this->hasData('default_filter')) {
			//// print_r($this->getData('default_filter'));die;
            //$this->setDefaultFilter($this->getData('default_filter'));
        //}
    //}
    protected function _prepareCollection()
    {
        if ($this->getCollection()) {
            foreach ($this->getDefaultFilter() as $field => $value) {
                $this->getCollection()->addFieldToFilter($field, $value);
            }
        }
        return parent::_prepareCollection();
    } 
}
