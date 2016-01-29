<?php
 
namespace Rapidmage\Firewall\Block\Adminhtml;
 
use Magento\Backend\Block\Widget\Grid\Container;
 
class Whiteip extends Container
{
   /**
     * Constructor
     *
     * @return void
     */
   protected function _construct()
    {
		$this->_controller = 'adminhtml_whiteip';
        $this->_blockGroup = 'Rapidmage_Firewall';
        $this->_headerText = __('Manage Ip');
        $this->_addButtonLabel = __('Add Whitelist IP');
        parent::_construct();
    }
}
