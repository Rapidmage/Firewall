<?php
 
namespace Rapidmage\Firewall\Controller\Adminhtml\Whiteip;
 
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
      // Get IDs of the selected Ips
      $ipIds = $this->getRequest()->getParam('ip');
 
        foreach ($ipIds as $ipId) {
            try {
               /** @var $ipModel \Rapidmage\Firewall\Model\IP */
                $ipModel = $this->_ipFactory->create();
                $ipModel->load($ipId)->delete();
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
 
        if (count($ipIds)) {
            $this->messageManager->addSuccess(
                __('A total of %1 record(s) were deleted.', count($ipIds))
            );
        }
 
        $this->_redirect('*/*/index');
   }
}
