<?php
 
namespace Rapidmage\Firewall\Block\Adminhtml;
 
use Magento\Backend\Block\Widget\Grid\Container;
 
class Blackip extends Container
{
   /**
     * Constructor
     *
     * @return void
     */
   protected function _construct()
    {
        $this->_controller = 'adminhtml_blackip';
        $this->_blockGroup = 'Rapidmage_Firewall';
        $this->_headerText = __('Manage Ip');
        $this->_addButtonLabel = __('Add blacklist IP');
        parent::_construct();
    }
}
