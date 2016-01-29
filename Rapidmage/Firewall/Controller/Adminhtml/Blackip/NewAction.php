<?php
 
namespace Rapidmage\Firewall\Controller\Adminhtml\Blackip;
 
class NewAction extends \Magento\Backend\App\Action
{
    protected $_coreRegistry;
 
    protected $_resultPageFactory;
 
    protected $_ipFactory;
 
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
     * Create new Ip action
     *
     * @return void
     */
   public function execute()
   {
      $this->_forward('edit');
   }
}
