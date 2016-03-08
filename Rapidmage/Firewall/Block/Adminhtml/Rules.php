<?php
namespace Rapidmage\Firewall\Block\Adminhtml;
use Magento\Backend\Block\Widget\Grid\Container;
 
class Rules extends Container
{
   /**
     * Constructor
     *
     * @return void
     */
   protected function _construct()
    {
        $this->_controller = 'adminhtml_rules';
        $this->_blockGroup = 'Rapidmage_Firewall';
        $this->_headerText = __('Manage Rules');
        $this->_addButtonLabel = __('Add Rules');
        parent::_construct();
    }
}
