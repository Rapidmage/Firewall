<?php
namespace Rapidmage\Firewall\Controller;

use Magento\Framework\App\RouterInterface;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ResponseInterface;
use Rapidmage\Firewall\Model\IpFactory;
use Rapidmage\Firewall\Model\Ipaccess;

class Router implements RouterInterface
{
    /**
     * @var \Magento\Framework\App\ActionFactory
     */
    protected $response;
    protected $actionFactory;
    protected $dispatched;
    protected $_ipFactory;
    protected $ipaccessObject;
    
    public function __construct(
         ActionFactory $actionFactory,
         ResponseInterface $response,
         IpFactory $ipFactory,
         Ipaccess $Ipaccess
        )
    {
		$this->ipaccessObject = $Ipaccess;
		$this->actionFactory = $actionFactory;
		$this->response = $response;
        $this->_ipFactory = $ipFactory;
       
    }

    /**
     * Validate and Match News Author and modify request
     * @param \Magento\Framework\App\RequestInterface $request
     * @return bool
     * //TODO: maybe remove this and use the url rewrite table.
     */
    public function match(\Magento\Framework\App\RequestInterface $request)
    {
		  if (!$this->dispatched) {
			$ipModel = $this->_ipFactory->create();      
            $ipCollection = $ipModel->getCollection();
            $ip = $_SERVER['REMOTE_ADDR'];
            $ip_access=$this->ipaccessObject->getIpaccess($ipModel);
            //echo $ip_access;die;
            //$ip_collections=$ipCollection->addFieldToFilter('ip_address',$ip)->getData(); //Get Ip collections      
            if ($ip_access==0) { // check whether an ip is in blacklist	//0 -Black, 1-White	 
			  
				  $request->setModuleName('cms')->setControllerName('page')->setActionName('view')->setParam('page_id', 1); 
				  $request->setDispatched(true);
	              $this->dispatched = true;
	              return $this->actionFactory->create(
	                    'Magento\Framework\App\Action\Forward',
	                    ['request' => $request]
	                );
				                 
	     }
       return null;  
	 }
        return null;
          
    }
}
