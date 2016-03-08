<?php
 
namespace Rapidmage\Firewall\Controller\Adminhtml\Rules;
 
use Rapidmage\Firewall\Controller\Adminhtml\Rules;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Rapidmage\Firewall\Model\RulesFactory;
 
class MassDelete extends \Magento\Backend\App\Action
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
      // Get IDs of the selected news
      $rulesIds = $this->getRequest()->getParam('rules');
 
        foreach ($rulesIds as $rulesId) {
            try {
               /** @var $newsModel \Mageworld\SimpleNews\Model\News */
                $rulesModel = $this->_rulesFactory->create();
                $rulesModel->load($rulesId)->delete();
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
 
        if (count($rulesIds)) {
            $this->messageManager->addSuccess(
                __('A total of %1 record(s) were deleted.', count($rulesIds))
            );
        }
 
        $this->_redirect('*/*/index');
   }
}
