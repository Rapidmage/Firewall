<?php
namespace Rapidmage\Firewall\Model;
use Magento\Framework\Event\ObserverInterface;
use Rapidmage\Firewall\Model\IpFactory;
class Observer implements ObserverInterface
{ 
	 
    protected $_ipFactory;
	public function __construct(
        IpFactory $ipFactory
        )
    {
		$this->_ipFactory = $ipFactory;
    }
    
   public function execute(\Magento\Framework\Event\Observer $observer)
    {
		$id=$_SERVER['REMOTE_ADDR'];
		$ipModel = $this->_ipFactory->create();      
        $whiteip_collection=$ipModel->getCollection()->addFieldToFilter('ip_address',$id)->addFieldToFilter('member_access',1)->getData();
       //print_r($whiteip_collection);die;
        if (empty(array_filter($whiteip_collection))) {      // To check ip in white list
			$om = \Magento\Framework\App\ObjectManager::getInstance();
			$cache = $om->get('Magento\Framework\App\CacheInterface');
		    $tags=array();$lifeTime=10800;
		    $val=$cache->load($id);
			if($val){
				if($val>2){
					$blackip_collection=$ipModel->getCollection()->addFieldToFilter('ip_address',$id)->addFieldToFilter('member_access',0)->getData();; 
					
					if (!empty(array_filter($blackip_collection))) {
						$ipModel->load($blackip_collection[0]['id']);
					}
					$ipModel->setIpAddress($id);
					$ipModel->setDescription('Too more login falied attempts');
					$ipModel->setMemberAccess(0);
					$ipModel->setFlag(1);
					$ipModel->save();
				}
				$count=(int)$val+1;			
				$cache->save($count, $id, $tags, $lifeTime);
			}
			else{
				$count=1;
				$cache->save($count, $id, $tags, $lifeTime);		
			}
			
		
			echo $cache->load($id);
	   }
    }
}
