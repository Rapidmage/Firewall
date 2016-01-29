<?php
 
namespace Rapidmage\Firewall\Controller\Adminhtml\Blackip;
 
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
 
    /**
     * Ip model factory
     *
     * @var \Rapidmage\Firewall\Model\IpFactory
     */
    protected $_ipFactory;
 
    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param IpFactory $IpFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Rapidmage\Firewall\Model\BlackipFactory $IpFactory
    ) {
       parent::__construct($context);
        $this->_coreRegistry = $coreRegistry;
        $this->_resultPageFactory = $resultPageFactory;
        $this->_ipFactory = $IpFactory;
    }
 
    /**
     * Ip access rights checking
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Rapidmage_Firewall::manage_ip');
    }
   /**
     * @return void
     */
   public function execute()
   {
      $isPost = $this->getRequest()->getPost();
 
      if ($isPost) {
         $ipModel = $this->_ipFactory->create();
         $ipId = $this->getRequest()->getParam('id');
 
         if ($ipId) {
            $ipModel->load($ipId);
         }
         $formData = $this->getRequest()->getParam('ip');
         $ipModel->setData($formData);
         
         try {
            // Save Ip
            $ipModel->save();
 
            // Display success message
            $this->messageManager->addSuccess(__('The IP has been saved.'));
 
            // Check if 'Save and Continue'
            if ($this->getRequest()->getParam('back')) {
               $this->_redirect('*/*/edit', ['id' => $ipModel->getId(), '_current' => true]);
               return;
            }
 
            // Go to grid page
            $this->_redirect('*/*/');
            return;
         } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
         }
 
         $this->_getSession()->setIpData($formData);
         $this->_redirect('*/*/edit', ['id' => $ipId]);
      }
   }
}
