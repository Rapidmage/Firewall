<?php
 
namespace Rapidmage\Firewall\Controller\Index;
 
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Rapidmage\Firewall\Model\IpFactory;
 
class Index extends Action
{
    /**
     * @var \Rapidmage\Firewall\Model\IpFactory
     */
    protected $_ipFactory;
 
    /**
     * @param Context $context
     * @param IpFactory $ipFactory
     */
    public function __construct(
        Context $context,
        IpFactory $ipFactory
    ) {
        parent::__construct($context);
        $this->_ipFactory = $ipFactory;
    }
 
    public function execute()
    {
        /**
         * When Magento get your model, it will generate a Factory class
         * for your model at var/generaton folder and we can get your
         * model by this way
         */
         
        $ipModel = $this->_ipFactory->create();
        // Load the item with ID is 1
        $item = $ipModel->load(1);
        var_dump($item->getData());
 
        // Get Ip collection
        $ipCollection = $ipModel->getCollection();
        // Load all data of collection
        var_dump($ipCollection->getData());
    }
}
