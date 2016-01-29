<?php
namespace Rapidmage\Firewall\Controller;

use Magento\Framework\App\RouterInterface;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ResponseInterface;
use Rapidmage\Firewall\Model\IpFactory;

class Router implements RouterInterface
{
    /**
     * @var \Magento\Framework\App\ActionFactory
     */
    protected $response;
    protected $actionFactory;
    protected $dispatched;
    protected $_ipFactory;
    
    public function __construct(
         ActionFactory $actionFactory,
         ResponseInterface $response,
         IpFactory $ipFactory
        )
    {
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
           // echo $ip;die;
            $whitelists=$ipCollection->addFieldToFilter('member_access', 1)->addFieldToFilter('ip_address',$ip); //Get Ip exist in whitelist
            $arrayfilter = array_filter($whitelists->getData()); // Remove all empty values
            if (empty($arrayfilter)) { // check whether an array is empty or not
				//echo "hello";die;
           
            //foreach($whitelists as $ip){
				//$whiteips[]=$ip->getIpAddress();
			//}
			
			//if (!in_array($ip, $whiteips)){	
		      $identifier = trim($request->getPathInfo(), '/');//echo $identifier;die;
			  
				// echo "wecome";die;
				
				//echo $ip;die;
			    //if($ip=='172.17.0.1'){
				  //$request->setModuleName('firewall')->setControllerName('ipblock')->setActionName('ipblock');
				  $request->setModuleName('cms')->setControllerName('page')->setActionName('view')->setParam('page_id', 1); 
				   $request->setDispatched(true);
	               $this->dispatched = true;
	               return $this->actionFactory->create(
	                    'Magento\Framework\App\Action\Forward',
	                    ['request' => $request]
	                );
				         
             //}
	     }
       return null;  
	 }
        return null;
          
    }
}
