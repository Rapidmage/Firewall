<?php
 
namespace Rapidmage\Firewall\Block\Adminhtml\Rules\Edit\Tab;
 
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Cms\Model\Wysiwyg\Config;
use Rapidmage\Firewall\Model\System\Config\Status;
 
class Info extends Generic implements TabInterface
{
    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;
 
    /**
     * @var \Tutorial\SimpleNews\Model\Config\Status
     */
    protected $_rulesStatus;
 
   /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Config $wysiwygConfig
     * @param Status $rulesStatus
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Config $wysiwygConfig,
        Status $rulesStatus,
        array $data = []
    ) { 
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_rulesStatus = $rulesStatus;
        parent::__construct($context, $registry, $formFactory, $data);
    }
 
    /**
     * Prepare form fields
     *
     * @return \Magento\Backend\Block\Widget\Form
     */
    protected function _prepareForm()
    {
       /** @var $model \Tutorial\SimpleNews\Model\News */
        $model = $this->_coreRegistry->registry('firewall_rules');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('rules_');
        $form->setFieldNameSuffix('rules');

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
            'who',
            'text',
            [
                'name'        => 'who',
                'label'    => __('Who'),
                'required'     => true
            ]
        );
        $fieldset->addField(
            'request',
            'textarea',
            [
                'name'      => 'request',
                'label'     => __('Request'),
                'required'     => true
            ]
        );
        $fieldset->addField(
            'what',
            'text',
            [
                'name'      => 'what',
                'label'     => __('what'),
                'required'  => true,
               
            ]
        );   
        $fieldset->addField(
            'why',
            'text',
            [
                'name'      => 'why',
                'label'     => __('Why'),
                'required'  => true,
                
            ]
        );
        $fieldset->addField(
            'level',
            'text',
            [
                'name'      => 'level',
                'label'     => __('Level'),
                'required'  => true,
               
            ]
        );
        $fieldset->addField(
            'enabled',
            'select',
            [
                'name'      => 'enabled',
                'label'     => __('Status'),
                'options'   => $this->_rulesStatus->toOptionArray()
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
        return __('Rules Info');
    }
 
    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Rules Info');
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
