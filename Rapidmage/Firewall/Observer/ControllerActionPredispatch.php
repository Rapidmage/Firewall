<?php
namespace Rapidmage\Firewall\Observer;
use Magento\Framework\Event\ObserverInterface;
use Rapidmage\Firewall\Model\IpFactory;
use Rapidmage\Firewall\Model\Ipaccess;
use Rapidmage\Firewall\Helper\Data;

class ControllerActionPredispatch implements ObserverInterface {
	
	protected $_scopeConfig;
	protected $_ipFactory;
	protected $ipaccessObject;
	protected $helper;
	

	public function __construct(         
           IpFactory $ipFactory,
           Ipaccess $Ipaccess,         
           Data $helper
         ) {
			 $this->ipaccessObject = $Ipaccess;           
             $this->_ipFactory = $ipFactory;
             $this->helper = $helper;
         }


	public function execute(\Magento\Framework\Event\Observer $observer) {
		
		$MagenfCheckDebug = '';
		if(!defined('STAG') && !defined('ETAG') && !defined('NF_STARTTIME')) {
			    define('STAG', '- ');
		        define('ETAG', "\n");
		        define('NF_STARTTIME', microtime(true));
		}
		if($this->helper->getOptionsData('console_mode_enable')==1) $MagenfCheckDebug = 2;
			if ($MagenfCheckDebug) { 
				$getIpOptionValue=$this->helper->getOptionsData('ban_attack_ip');
				$CheckipOption = ($getIpOptionValue==0) ? 'off' : 'on';
				
		        $ip_address = $this->helper->getClientIp();
		        $nfdebug = STAG ."starting Firewall". ETAG ;
		        
		        /* Start - Check Black ip try to access */
		        $ipModel = $this->_ipFactory->create();
		        $ipCollection = $ipModel->getCollection();
                $ip_access=$this->ipaccessObject->getIpaccess($ipModel);
                if($ip_access==0){
			          $this->helper->nf_write2log('Blacklist Ip trying to get site.', null, 2, 0);
			          $this->helper->nf_block();
			    }
                /* End - Check Black ip try to access */
            
                /* Start - checking user IP */
                if ((preg_match('/^(?:::ffff:)?127\.0\.0\.1$/', $ip_address)) || ($ip_address == $_SERVER['SERVER_ADDR'])) {
				    define('NFDEBUG', $nfdebug.= '[STOP]   '. $ip_address .' is whitelisted'. ETAG . '::' . $this->helper->nf_benchmarks(NF_STARTTIME) );
				   return;
				}
                /* End - checking user IP */
                
                $nfdebug.= "[----]   banning IP option is $CheckipOption". ETAG; 
                /* Need to inspect 
				if (($_SERVER['SCRIPT_FILENAME'] == dirname(__FILE__) .'/index.php') || ($_SERVER['SCRIPT_FILENAME'] == dirname(__FILE__) .'/login.php') ) {		
				    define('NFDEBUG', $nfdebug.= STAG ."script is whitelisted\t\t[STOP]   ".$_SERVER['SCRIPT_NAME']. ETAG . '::' . $this->helper->nf_benchmarks(NF_STARTTIME) ); 
				   return;
				}*/
				//$_SERVER['SCRIPT_FILENAME']  -  /var/www/html/projects/magento2/index.php
				//dirname(__FILE__)  - /var/www/html/projects/magento2/app/code/Rapidmage/Firewall/Observer
                 //echo $_SERVER['SCRIPT_FILENAME'];die;
		        /* Start - Check - Request Method */ 
				if ( strpos('GET|POST|HEAD', $_SERVER['REQUEST_METHOD']) === false ) {
				  
					  $nfdebug.="REQUEST_METHOD\t\t[FAIL]   ". $this->helper->nf_bin2hex_string($_SERVER['REQUEST_METHOD']) .' not allowed';
					  $this->helper->nf_write2log('request method not allowed', $_SERVER['REQUEST_METHOD'], 2, 0);
				      $this->helper->nf_block();
				}
				 /* End - Check - Request Method */ 
				 /* Start - Check whether Host is ip or domain */ 
				if (preg_match('/^[\d.:]+$/', $_SERVER['HTTP_HOST'])) {//echo "welcome";die;
					if ($MagenfCheckDebug) { $nfdebug.= STAG ."HTTP_HOST\t\t\t[FAIL]   HTTP_HOST is an IP (".$_SERVER['HTTP_HOST']  .')'. ETAG; }
					$this->helper->nf_write2log('HTTP_HOST is an IP', $_SERVER['HTTP_HOST'], 1, 0);
					if($getIpOptionValue==1){
						$this->helper->nf_block();
					}
				}
				 /* End - Check whether Host is ip or domain */
			    $this->helper->nf_check_request();  // Check Firewall rules
			    $nfdebug.= STAG ."checking uploads\t\t"; 			  
				if (!empty($_FILES)) {
				   $this->helper->nf_check_upload();
				} else {			
				    $nfdebug.= "[----]   no upload detected". ETAG; 
				}
				$_GET = $this->helper->nf_sanitise( $_GET, 1, 'GET');
				$_COOKIE = $this->helper->nf_sanitise( $_COOKIE, 1, 'COOKIE');
				if (!empty($_SERVER['HTTP_USER_AGENT'])) {
					$_SERVER['HTTP_USER_AGENT'] = $this->helper->nf_sanitise( $_SERVER['HTTP_USER_AGENT'], 1, 'HTTP_USER_AGENT');
				}
				if (!empty($_SERVER['HTTP_REFERER'])) {
					$_SERVER['HTTP_REFERER'] = $this->helper->nf_sanitise( $_SERVER['HTTP_REFERER'], 1, 'HTTP_REFERER');
				}
				if (!empty($_SERVER['PATH_INFO'])) {
					$_SERVER['PATH_INFO'] = $this->helper->nf_sanitise( $_SERVER['PATH_INFO'], 2, 'PATH_INFO');
				}
				if (!empty($_SERVER['PATH_TRANSLATED'])) {
					$_SERVER['PATH_TRANSLATED'] = $this->helper->nf_sanitise( $_SERVER['PATH_TRANSLATED'], 2, 'PATH_TRANSLATED');
				}
				if (!empty($_SERVER['PHP_SELF'])) {
					$_SERVER['PHP_SELF'] = $this->helper->nf_sanitise( $_SERVER['PHP_SELF'], 2, 'PHP_SELF');
				}
				
				if ( (!defined('NFDEBUG')) && ($nfdebug) ) { define('NFDEBUG',$nfdebug . '::' . $this->helper->nf_benchmarks(NF_STARTTIME) ); }
				$this->helper->nf_debugfirewall($MagenfCheckDebug);
				return;

            }
            else{
              define('NFDEBUG', $nfdebug.= STAG ."protection is disabled\t[STOP]". ETAG . '::' . $this->helper->nf_benchmarks(NF_STARTTIME) ); 
		 }
	}	
	
}
