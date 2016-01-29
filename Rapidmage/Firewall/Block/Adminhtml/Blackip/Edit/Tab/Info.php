<?php
 
namespace Rapidmage\Firewall\Block\Adminhtml\Blackip\Edit\Tab;
 
use Magento\Backend\Block\Widget\Form\Generic;

use Rapidmage\Firewall\Model\System\Config\Status;
 
class Info extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
   
 
   /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;
    protected $_ipStatus;
    
 
   /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Config $wysiwygConfig
     * @param Status $IPStatus
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Rapidmage\Firewall\Model\System\Config\Status $ipStatus,
        array $data = []
    ) {
		
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_ipStatus = $ipStatus;
        parent::__construct($context, $registry, $formFactory, $data);
         
    }
 
 
    /**
     * Prepare form fields
     *
     * @return \Magento\Backend\Block\Widget\Form
     */
    protected function _prepareForm()
    {
		
       /** @var $model \Rapidmage\Firewall\Model\Ip */
        $model = $this->_coreRegistry->registry('firewall_blackip');
 
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('ip_');
        $form->setFieldNameSuffix('ip');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General')]
        );
        
 
        if ($model->getId()) {
            $fieldset->addField(
                'id',
                'hidden',
                ['name' => 'id']
            );
        }
    
        $fieldset->addField(
            'ip_address',
            'text',
            [
                'name'        => 'ip_address',
                'label'    => __('IP Address'),
                'required'     => true
            ]
        );
              
        $fieldset->addField(
            'description',
            'textarea',
            [
                'name'      => 'description',
                'label'     => __('Description'),
                'style'     => 'height: 15em; width: 30em;'
            ]
        );
     
       $fieldset->addField(
            'member_access',
            'select',
            [
                'name'      => 'member_access',
                'label'     => __('Status'),
                'options'   => $this->_ipStatus->toBlackListArray()
            ]
        );
        $data = $model->getData();
        $form->setValues($data);
        $this->setForm($form);
 
        return parent::_prepareForm();
    }
 
    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('IP Info');
    }
 
    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('IP Info');
    }
 
    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }
 
    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }
}
