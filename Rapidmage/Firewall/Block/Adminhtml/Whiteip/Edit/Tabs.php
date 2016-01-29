<?php
 
namespace Rapidmage\Firewall\Block\Adminhtml\Whiteip\Edit;
 
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * Class constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('ip_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Ip Information'));
    }
 
    /**
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'ip_info',
            [
                'label' => __('General'),
                'title' => __('General'),
                'content' => $this->getLayout()->createBlock(
                   'Rapidmage\Firewall\Block\Adminhtml\Whiteip\Edit\Tab\Info'
                )->toHtml(),
                'active' => true
            ]
        );
        return parent::_beforeToHtml();
    }
}
