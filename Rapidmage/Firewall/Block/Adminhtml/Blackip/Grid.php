<?php
 
namespace Rapidmage\Firewall\Block\Adminhtml\Blackip;
 
use Magento\Backend\Block\Widget\Grid as WidgetGrid;
 
class Grid extends WidgetGrid
{
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
