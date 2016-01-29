<?php
 
namespace Rapidmage\Firewall\Controller\Adminhtml\Whiteip;
 
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
      $ipId = $this->getRequest()->getParam('id');
        /** @var \Rapidmage\Firewall\Model\Ip $model */
        $model = $this->_ipFactory->create();
 
        if ($ipId) {
            $model->load($ipId);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This Ip no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }
 
        // Restore previously entered form data from session
        $data = $this->_session->getIpData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        $this->_coreRegistry->register('firewall_ip', $model);
 
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Rapidmage_Firewall::main_menu');
        $resultPage->getConfig()->getTitle()->prepend(__('Firewall'));
 
        return $resultPage;
   }
}
