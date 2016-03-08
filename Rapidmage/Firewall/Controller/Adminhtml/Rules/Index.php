<?php
 
namespace Rapidmage\Firewall\Controller\Adminhtml\Rules;
 
class Index extends \Magento\Backend\App\Action
{/**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;
 
    /**
     * Result page factory
     *
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;
 
    
    protected $_rulesFactory;
 
    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param RulesFactory $newsFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Rapidmage\Firewall\Model\RulesFactory $rulesFactory
    ) {
		
       parent::__construct($context);
        $this->_coreRegistry = $coreRegistry;
        $this->_resultPageFactory = $resultPageFactory;
        $this->_rulesFactory = $rulesFactory;
    }
 
    /**
     * News access rights checking
     *
     * @return bool
     */
    protected function _isAllowed()
    {
		
        return $this->_authorization->isAllowed('Rapidmage_Firewall::manage_rules');
    }
    /**
     * @return void
     */
   public function execute()
   {
	   
      if ($this->getRequest()->getQuery('ajax')) {
            $this->_forward('grid');
            return;
        }
        
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create(); 
        $resultPage->setActiveMenu('Rapidmage_Firewall::main_menu');
        $resultPage->getConfig()->getTitle()->prepend(__('Firewall Rules'));

        return $resultPage;
   }
}
