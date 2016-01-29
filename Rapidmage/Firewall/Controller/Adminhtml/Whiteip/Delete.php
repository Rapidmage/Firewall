<?php
 
namespace Rapidmage\Firewall\Controller\Adminhtml\Whiteip;
 
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
        \Rapidmage\Firewall\Model\WhiteipFactory $IpFactory
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
      $ipId = (int) $this->getRequest()->getParam('id');
 
      if ($ipId) {
         /** @var $ipModel \Rapidmage\Firewall\Model\Ip */
         $ipModel = $this->_ipFactory->create();
         $ipModel->load($ipId);
 
         // Check this IP exists or not
         if (!$ipModel->getId()) {
            $this->messageManager->addError(__('This IP no longer exists.'));
         } else {
               try {
                  // Delete IP
                  $ipModel->delete();
                  $this->messageManager->addSuccess(__('The IP has been deleted.'));
 
                  // Redirect to grid page
                  $this->_redirect('*/*/');
                  return;
               } catch (\Exception $e) {
                   $this->messageManager->addError($e->getMessage());
                   $this->_redirect('*/*/edit', ['id' => $ipModel->getId()]);
               }
            }
      }
   }
}
 
