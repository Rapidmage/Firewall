<?php
 
namespace Rapidmage\Firewall\Controller\Adminhtml\Rules;
 
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Rapidmage\Firewall\Model\RulesFactory;
 
class Edit extends \Magento\Backend\App\Action
{
   /**
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
 
    
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        RulesFactory $rulesFactory
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
   public function execute()
   {
	 
      $rulesId = $this->getRequest()->getParam('id');
        /** @var \Rapidmage\Firewall\Model\Rules $model */
        $model = $this->_rulesFactory->create();

        if ($rulesId) {
            $model->load($rulesId);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This rules no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        } 
 
        // Restore previously entered form data from session
        $data = $this->_session->getRulesData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        $this->_coreRegistry->register('firewall_rules', $model);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Rapidmage_Firewall::main_menu');
        $resultPage->getConfig()->getTitle()->prepend(__('Firewall'));
        return $resultPage;
   }
}
