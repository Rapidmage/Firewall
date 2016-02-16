<?php
namespace Rapidmage\Firewall\Model;
use Magento\Framework\Event\ObserverInterface;
use Rapidmage\Firewall\Model\IpFactory;
use Magento\Framework\Controller\Result\Redirect;
class Observer implements ObserverInterface
{ 
	protected $ipaccessObject;
    protected $_ipFactory;
	public function __construct(
        IpFactory $ipFactory
        )
    {
		$object = new Ipaccess; 
		$this->ipaccessObject = $object;
		$this->_ipFactory = $ipFactory;
    }
    
   public function execute(\Magento\Framework\Event\Observer $observer)
    {
		$id=$_SERVER['REMOTE_ADDR'];
		$ipModel = $this->_ipFactory->create();
		echo $ip_access=$this->ipaccessObject->getIpaccess($ipModel);die;
		if($ip_access==1){
			return;
		}
		elseif($ip_access==0){
			//return $this->resultRedirectFactory->create()->setUrl($this->_redirect->getRedirectUrl());
			//Redirect to 404 page
			}
			else{
				if($ip_access==2){
					$get_ip=$ipModel->getCollection()->addFieldToFilter('ip_address',$id)->getData();
					$count=$get_ip[0]['req_count'];
					$ipModel->load($get_ip[0]['id']);
					$ipModel->setReqCount($count+1);
					if($count>2){
						$ipModel->setDescription('Too more login falied attempts');
				        $ipModel->setMemberAccess(0);
					}
				}
				else{
					$ipModel->setIpAddress($id);
					$ipModel->setReqCount(1);
					$ipModel->setDescription('Failed Login attempts');
					$ipModel->setMemberAccess(2);  // Neither Black nor white ip
					$ipModel->setFlag(1);
				}
				$ipModel->save();	
		   }
	 }
}
