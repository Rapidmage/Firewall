<?php
 
namespace Rapidmage\Firewall\Controller\Adminhtml\Rules;
 
use Rapidmage\Firewall\Controller\Adminhtml\Rules;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Rapidmage\Firewall\Model\RulesFactory;
  
class NewAction extends \Magento\Backend\App\Action
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
     * Create new rules action
     *
     * @return void
     */
   public function execute()
   {
      $this->_forward('edit');
   }
}
