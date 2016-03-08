<?php
 
namespace Rapidmage\Firewall\Controller\Adminhtml\Rules;
 
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Rapidmage\Firewall\Model\RulesFactory;
 
class Save extends \Magento\Backend\App\Action
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
   /**
     * @return void
     */
   public function execute()
   {
      $isPost = $this->getRequest()->getPost();
 
      if ($isPost) {
         $rulesModel = $this->_rulesFactory->create();
         $rulesId = $this->getRequest()->getParam('id');
 
         if ($rulesId) {
            $rulesModel->load($rulesId);
         }
         $formData = $this->getRequest()->getParam('rules');
         $rulesModel->setData($formData);
         
         try {
            // Save Rules
            $rulesModel->save();
 
            // Display success message
            $this->messageManager->addSuccess(__('The rules has been saved.'));
 
            // Check if 'Save and Continue'
            if ($this->getRequest()->getParam('back')) {
               $this->_redirect('*/*/edit', ['id' => $rulesModel->getId(), '_current' => true]);
               return;
            }
 
            // Go to grid page
            $this->_redirect('*/*/');
            return;
         } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
         }
 
         $this->_getSession()->setFormData($formData);
         $this->_redirect('*/*/edit', ['id' => $newsId]);
      }
   }
}
