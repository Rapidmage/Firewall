<?php
 
namespace Rapidmage\Firewall\Controller\Adminhtml\Dashboard;
  
class Scan extends \Magento\Backend\App\Action
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
    
    protected $_storeManager;
    
    //protected $_urlinterface;
 
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
        \Magento\Store\Model\StoreManagerInterface $storeManager
        //\Magento\Framework\UrlInterface $urlinterface
     ) {
        parent::__construct($context);
        $this->_coreRegistry = $coreRegistry;
        $this->_resultPageFactory = $resultPageFactory;
        $this->_storeManager = $storeManager;
        //$this->_urlinterface = $urlinterface;
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
      $resultPage = $this->_resultPageFactory->create();
      //echo $this->_storeManager->getStore()->getBaseUrl();die;
	  return $resultPage;
   }
	public function getBaseUrl()
	{
		return $this->_storeManager->getStore()->getBaseUrl();
	}
	/*public function getCurrentUrl()
	{
		return $this->_urlinterface->getCurrentUrl(); // Give the current url of recently viewed page
	} */

}
