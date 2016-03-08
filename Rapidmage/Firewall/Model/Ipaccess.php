<?php
namespace Rapidmage\Firewall\Model;
class Ipaccess
{
   public function getIpaccess($ipModel)
    {
        $id=$_SERVER['REMOTE_ADDR'];
        $ip_collections=$ipModel->getCollection()->addFieldToFilter('ip_address',$id)->getData();
        if(empty(array_filter($ip_collections))){
			/* Start - Ip range check */
			$iprange_collection=$ipModel->getCollection()->getData();
			foreach($iprange_collection as $ip_range){
				if (strpos($ip_range['ip_address'], '-') !== false) {
					list($min, $max) = explode('-', $ip_range['ip_address'], 2); 
					if (strpos($min, '*') !== false) {$min=str_replace('*',0,$min);}	    
					if (strpos($max, '*') !== false) {$max=str_replace('*',0,$max);}		    
			        if((ip2long($min) <= ip2long($id) && ip2long($id) <= ip2long($max))){
						if($ip_range['member_access']==1){
							return 1; // White Ip
						}
						elseif($ip_range['member_access']==0){
							return 0; // Black Ip
						}
					}
	            }
	        }
	        /* End - Ip range check */
	        return 3; // IP not exist
	    }
		else{
		    if($ip_collections[0]['member_access']==1){return 1;} // White Ip
	        elseif($ip_collections[0]['member_access']==2){return 2;} // Neither Black IP nor White IP
	        else{return 0;}	 // Black IP     	        									     			      		      
		 }
	     
     }
}

