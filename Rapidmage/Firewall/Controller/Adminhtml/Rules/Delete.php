<?php
 
namespace Rapidmage\Firewall\Controller\Adminhtml\Rules;
 
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Rapidmage\Firewall\Model\RulesFactory;
 
class Delete extends \Magento\Backend\App\Action
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
      $rulesId = (int) $this->getRequest()->getParam('id');
 
      if ($rulesId) {
         /** @var $newsModel \Mageworld\SimpleNews\Model\News */
         $rulesModel = $this->_rulesFactory->create();
         $rulesModel->load($rulesId);
 
         // Check this news exists or not
         if (!$rulesModel->getId()) {
            $this->messageManager->addError(__('This rules no longer exists.'));
         } else {
               try {
                  // Delete news
                  $rulesModel->delete();
                  $this->messageManager->addSuccess(__('The rules has been deleted.'));
 
                  // Redirect to grid page
                  $this->_redirect('*/*/');
                  return;
               } catch (\Exception $e) {
                   $this->messageManager->addError($e->getMessage());
                   $this->_redirect('*/*/edit', ['id' => $rulesModel->getId()]);
               }
            }
      }
   }
}
