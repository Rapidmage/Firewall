<?php
namespace Rapidmage\Firewall\Helper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Rapidmage\Firewall\Logger\Logger;
use Rapidmage\Firewall\Model\RulesFactory;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
	protected $scopeConfig;
	protected $_logger;
	protected $_rulesFactory;
	public function __construct(
           ScopeConfigInterface $scopeConfig,
           Logger $logger,
           RulesFactory $_rulesFactory        
         ) {
			  $this->scopeConfig = $scopeConfig;
			  $this->_logger = $logger; 
			  $this->_rulesFactory = $_rulesFactory;         
         }
    public function getOptionsData($fieldtext)
    {
		$storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
		$path='firewall/ip_block/'.$fieldtext;
        return $this->scopeConfig->getValue($path, $storeScope);
    }
    
    public function getClientIp(){
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
			$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip_address = $_SERVER['REMOTE_ADDR'];
		}
		return $ip_address;		
	}
    
    public function nf_bin2hex_string($data) {
		$res = '';
		$string = str_split($data);
		foreach ( $string as $char ) {
			if ( ( ord($char) < 32 ) || ( ord($char) > 127 ) ) {
				$res .= '%' . bin2hex($char);
			} else {
				$res .= $char;
			}
		}
		return $res;
	}
	public function nf_write2log( $loginfo, $logdata, $loglevel, $ruleid ) {
	   global $MagenfCheckDebug;
	   global $rand_value;
	   global $nfdebug;
	   global $ip_address;
	
		if ( ($loglevel == 6) || ($loglevel == 5) ) {
			$rand_value = '0000000';
			$http_ret_code = '200 OK';
		} else {
			$rand_value = mt_rand(1000000, 9000000);
			$http_ret_code = '403 Forbidden';
		}	
	    $message =  
	      '[' . $http_ret_code . '] ' . '[' . $_SERVER['REQUEST_METHOD'] . '] ' .
	      '[' . $_SERVER['SCRIPT_NAME'] . '] ' . '[' . $loginfo . '] ' .
	      '[' . $this->nf_bin2hex_string($logdata) . ']' . "\n";
	  // Mage::getModel('firewall/logs')
	      //  ->setData(array('summary'=>$message,'ruleid'=>$ruleid,'level'=>$loglevel,'ip'=>$ip_address,'incidentid'=>$rand_value,'created_time'=>time()))
	       // ->save();
	   $this->_logger->info($message);
	   //Mage::log($message, null, "firewall_-".date('Y-m-d').".log");
	  // fclose($handle);
	}
	   public function nf_block() {
	   global $nfdebug;
	   global $rand_value;
	   global $ip_address;
	
	   header('HTTP/1.1 403 Forbidden');
		header('Status: 403 Forbidden');
		echo '<html><head><title>403 Forbidden</title><style>.smallblack{font-family:Verdana,Arial,Helvetica,Ubuntu,"Bitstream Vera Sans",sans-serif;font-size:12px;line-height:16px;color:#000000;}.tinygrey{font-family:Verdana,Arial,Helvetica,Ubuntu, "Bitstream Vera Sans",sans-serif;font-size:10px;line-height:12px;color:#999999;}</style></head><body><br><br><br><br><br><table align=center style="border:1px solid #FDCD25;" cellspacing=0 cellpadding=6 class=smallblack><tr><td align=center><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAA3XAAAN1wFCKJt4AAAAB3RJTUUH1goFFS4tIeiJwwAAAqNJREFUOMttk9trXFUUxn97nz0zZ+ZEEnNFwSAhVQoKEcFCqaJ9CQhBnxRC639QCvqgkJdQGoo3fBbxQmlLzIPoQ0SKEAQlITEEL6mkScUmJ2MymclJzlzOTM6cs3w4U3OhH+yXtdb3fWvtxVKcwPI4g20Z3nW6H30509HvgCLcc2tVr/hT4PPRU1f5i4dhZhzjfpz+Mpx/25fdJZE4kkPEIt7vEi68429+kr3x66ekjpFlCqvwef+8FH6OJPRFQl+q+9vy9eQtuXnjupQK6/IgLsW5aOeLJxeXx0kDGIC8y2ePvzb+HCanKa8BsHLnH+YXFjHG0NUmDL/0bOKm07r7/MRQ+O1bX0E0au59wKmegadHMI6hsgoa0FD11snn81iWxc52BglslAAxoIzuHjg9vPbhn88Yx+G9VC7dTbCW9KMTo9r+Jq7rorVmr2ghQRqlFQgQQiqb7mxvY8zkOjgHGxCuQgSoRCQob+O6LkopdosCByrJCUkd97HbecGYHDZ6D6qzYPf8L1ArF9nY2Eg62G0iYQRKJSM0SqBLGIeMUVkUFnBwF9QWpOxkM3GdZrMJQHhQBGkk5LAOB34yrg1GFA1SgCWg90Htg4InHjtcc19PDaVqrS0AKaAJommYsMFstpNBaCVM8l48C79MgbsFr5wBbbdm161/MhB5LOlmhfdjGx/TEkgBaVhx4c3LcOkK/PF3EjtqIFnKUcA13TXKcrDDD7QRYx2KfHMb3H+hUITJaYj0EQGHuF7gx443WNQATpGLjT2WeIQIKyl6fQT6eqGrE0ZeBcsGrITc8FjOVrhw/B6+J1OfYyr28MRHxEfiKhIHiFQQKSOxh1ef5zuZwX7AUyevsjLNUKqXMauL51WOtIAioB6V+K1ZYsIZZvFo/X+fTjL6xSvBJAAAAABJRU5ErkJggg==" border=0 width=16 height=16><p>Sorry <b>'. $ip_address .'</b>, your request cannot be proceeded.<br>For security reason it was blocked and logged.<p>If you think that this was a mistake, please contact<br>the webmaster and enclose the following incident ID&nbsp;:<p>[<b>#' . $rand_value . '</b>]<br>&nbsp;</td></tr></table><br><br><br><br></body></html>';
	
	   if ($nfdebug) {define('NFDEBUG', $nfdebug . '::' . $this->nf_benchmarks() );}
	
		//@$dbh->close();
	   exit;
	}
	function nf_benchmarks($start_time) {
       return round( (microtime(true) - $start_time), 5);
    }
    function nf_check_request() {
		if(!defined('STAG') && !defined('ETAG') && !defined('NF_STARTTIME')) {
			    define('STAG', '- ');
		        define('ETAG', "\n");
		        define('NF_STARTTIME', microtime(true));
		}
		$rules_count = 0;
		$rulesModel = $this->_rulesFactory->create();
		$ruleCollection = $rulesModel->getCollection()->getData();
		//print_r($ruleCollection);die; 
		foreach($ruleCollection as $rulesData){
			$wherelist = explode('|', $rulesData['request']);	
			foreach ($wherelist as $where) {
			
				if ( ($where == 'POST') || ($where == 'GET') ) {
					foreach ($GLOBALS['_' . $where] as $reqkey => $reqvalue) {
	               if (is_array($reqvalue) ) {
	                  $res = $this->nf_flatten( "\n", $reqvalue );
	                  $reqvalue = $res;
	                 
	                  $rulesData['what'] = '(?m:'. $rulesData['what'] .')';
	               } else {
							if ( ($where == 'POST') && ($reqvalue) && (! isset( $b64_post[$reqkey])) ) {
								$b64_post[$reqkey] = 1;
								$this->nf_check_b64($reqkey, $reqvalue);
							}
						}
						// print_r("reqvalue." .$reqvalue ."=" );
	               if (!$reqvalue) {continue;}
	               $rules_count++;
	               
	               //print_r($rulesData['what'] . "<br />");die;
	               if ( preg_match('`'.$rulesData['what'].'`', $reqvalue) ) {
					   
	                  if ($MagenfCheckDebug) { $nfdebug.= STAG ."checking request\t\t". '[FAIL]   '. $where .' : ' . $rulesData['why'] . ' (#'. $rulesData['rules_id'] . ')' . ETAG; }
	                 
	                  $this->nf_write2log($rulesData['why'], $where . ':' . $reqkey . ' = ' . $reqvalue, $rulesData['level'], $rulesData['rules_id']);
	                  $this->nf_block();
	               } 
	               
				   
	            }
					continue;
				}
	
				$sub_value = explode(':', $where);
				if ( (! empty($sub_value[1]) ) && ( @isset($GLOBALS['_' . $sub_value[0]] [$sub_value[1]]) ) ) {
					$rules_count++;
					if ( is_array($GLOBALS['_' . $sub_value[0]] [$sub_value[1]]) ) {
	               $res = $this->nf_flatten( "\n", $GLOBALS['_' . $sub_value[0]] [$sub_value[1]] );
	               $GLOBALS['_' . $sub_value[0]] [$sub_value[1]] = $res;
	               $rulesData['what'] = '(?m:'. $rulesData['what'] .')';
	            }
	            if (! $GLOBALS['_' . $sub_value[0]] [$sub_value[1]]) {continue;}
					if ( preg_match('`'. $rulesData['what'] .'`', $GLOBALS['_' . $sub_value[0]] [$sub_value[1]]) ) {
						if ($MagenfCheckDebug) { $nfdebug.= STAG ."checking request\t\t". '[FAIL]   '.$sub_value[0].':'.$sub_value[1].' : ' . $rulesData['why'] . ' (#'. $rulesData['rules_id'] . ')' . ETAG; }
						$this->nf_write2log($rulesData['why'], $sub_value[0].':'.$sub_value[1].' = ' . $GLOBALS['_' . $sub_value[0]] [$sub_value[1]], $rulesData['level'], $rulesData['rules_id']);
						$this->nf_block();
					}
	
	         } elseif ( isset($_SERVER[$where]) ) {
	            $rules_count++;
					if ( preg_match('`'. $rulesData['what'] .'`', $_SERVER[$where]) ) {
	               if ($MagenfCheckDebug) { $nfdebug.= STAG ."checking request\t\t". '[FAIL]   ' . $where.' : ' . $rulesData['why'] . ' (#'. $rulesData['rules_id'] . ')' . ETAG; }
	               $this->nf_write2log($rulesData['why'], $where . ':' . $_SERVER[$where], $rulesData['level'], $rulesData['rules_id']);
	               $this->nf_block();
	            }
	         }
	      }
		}  
	}
	function nf_flatten($glue, $pieces) {
	   foreach ($pieces as $r_pieces) {
	      if ( is_array($r_pieces)) {
	         $ret[] = $this->nf_flatten($glue, $r_pieces);
	      } else {
	         $ret[] = $r_pieces;
	      }
	   }
	   return implode($glue, $ret);
	}
	function nf_check_b64( $reqkey, $string ) {
		global $nfdebug;
	
		$string = preg_replace( '`[^A-Za-z0-9+/=]`', '', $string);
		if ( (! $string) || (strlen($string) % 4 != 0) ) { return; }
		if ( base64_encode( $decoded = base64_decode($string) ) === $string ) {
			if ( preg_match( '`\b(?:\$?_(COOKIE|ENV|FILES|(?:GE|POS|REQUES)T|SE(RVER|SSION))|HTTP_(?:(?:POST|GET)_VARS|RAW_POST_DATA)|GLOBALS)\s*[=\[)]|\b(?i:array_map|assert|base64_(?:de|en)code|chmod|curl_exec|(?:ex|im)plode|error_reporting|eval|file(?:_get_contents)?|f(?:open|write|close)|fsockopen|function_exists|gzinflate|md5|move_uploaded_file|ob_start|passthru|preg_replace|phpinfo|stripslashes|strrev|(?:shell_)?exec|system|unlink)\s*\(|\becho\s*[\'"]|<\s*(?i:applet|div|embed|i?frame(?:set)?|img|meta|marquee|object|script|textarea)\b|\b(?i:(?:ht|f)tps?|php)://|\W\$\{\s*[\'"]\w+[\'"]|<\?(?i:php)`', $decoded) ) {
			    $nfdebug.= STAG ."checking request\t\t". '[FAIL]   POST[' . $reqkey . '] : BASE64-encoded injection' . ETAG;
				$this->nf_write2log('BASE64-encoded injection', 'POST:' . $reqkey . ' = ' . $string, 3, 0);
				$this->nf_block();
			}
		}
	}
	function nf_check_upload() {
	   global $nfdebug;
	   $tmp = '';
		foreach ($_FILES as $file) {
			if ( is_array($file['name']) ) {
				foreach($file['name'] as $key => $value) {
					if (!$file['name'][$key]) { continue; }
					$tmp .= $file['name'][$key] . ', ' . number_format($file['size'][$key]) . ' bytes ';
				}
			} else {
				if (!$file['name']) { continue; }
				$tmp .= $file['name'] . ', ' . number_format($file['size']) . ' bytes ';
			}
		}
	   if ($tmp) {
			$nfdebug.= '[FAIL]   file upload attempt : '. $this->nf_bin2hex_string($tmp) . ETAG; }
			$this->nf_write2log('File upload attempt', rtrim($tmp, ' '), 2, 0);
	
	   $nfdebug.= '[----]   upload field is empty' . ETAG;
	}
 
	function nf_sanitise($str, $how, $msg ) {
	//	global $dbh;
		global $nfdebug;
		if (! isset($str) ) {
			return null;
		} else if (is_string($str) ) {
			if (get_magic_quotes_gpc() ) {$str = stripslashes($str);}
	
			if ($how == 1) {
				//$str2 = $dbh->real_escape_string($str);
				$str2 = str_replace('`', '\`', $str);
			} else {
				$str2 = str_replace(	array('\\', "'", '"', "\x0d", "\x0a", "\x00", "\x1a", '`', '<', '>'),
					array('\\\\', "\\'", '\\"', 'X', 'X', 'X', 'X', '\\`', '\\<', '\\>'),	$str);
			}
			if ($str2 != $str) {
				$this->nf_write2log('Sanitising user input', $msg . ': ' . $str, 6, 0);
				if ($MagenfCheckDebug) { $nfdebug.= STAG . "sanitising $msg\t\t[WARN]   string: " . $this->nf_bin2hex_string($str) . ETAG; }
			}
			return $str2;
	
		} else if (is_array($str) ) {
			foreach($str as $key => $value) {
				if (get_magic_quotes_gpc() ) {$key = stripslashes($key);}
	
				$key2 = str_replace(	array('\\', "'", '"', "\x0d", "\x0a", "\x00", "\x1a", '`', '<', '>'),
					array('\\\\', "\\'", '\\"', 'X', 'X', 'X', 'X', '&#96;', '&lt;', '&gt;'),	$key, $occ);
				if ($occ) {
					unset($str[$key]);
					$this->nf_write2log('Sanitising user input', $msg . ': ' . $key, 6, 0);
					if ($MagenfCheckDebug) { $nfdebug.= STAG . "sanitising $msg\t\t[WARN]   string: " . $this->nf_bin2hex_string($key) . ETAG; }
				}
				$str[$key2] = $this->nf_sanitise($value, $how, $msg);
			}
			return $str;
		}
	}
	function nf_debugfirewall($debug) {

		if ( (defined('NF_NODBG')) || (! defined('NFDEBUG')) || (NFDEBUG == '') ) {
			return;
		}
		list($nfdebug, $bench) = explode('::', NFDEBUG . '::');
	   if ($debug == 1) {
	      echo "\n<!--\n". htmlentities( $nfdebug ) ."- stopping Firewall\n- processing time:\t\t$bench s\n-->"  ;
	   } else {
			echo '<br><script>function onoff(){if(document.getElementById("tex").style.display=="none"){document.getElementById("tex").style.display="";document.getElementById("fie").style.background="#000000";document.cookie="tex=0; expires=Thu, 01-Jan-70 00:00:01 GMT;";}else{document.getElementById("tex").style.display="none";document.getElementById("fie").style.background="none";document.cookie="tex=1;";}}</script>'. "\n". '<center><fieldset id=fie style="width:85%;font-family:Verdana,Arial,sans-serif,Ubuntu;font-size:10px;background:';
			if ( (isset($_COOKIE['tex'])) && ($_COOKIE['tex'])==1) {echo 'none';} else {echo '#000000';}
			echo ';border:0px solid #000000;padding:0px;"><legend id=leg style="border:1px solid #ffd821;background:#ffd821;font-family:Verdana,Arial,sans-serif,Ubuntu;font-size:10px;"><a title=\'Click to mask/show the console\' href="javascript:onoff();" style="text-decoration: none;color:#000000;background:#ffd821;"><b>&nbsp;Firewall debug console&nbsp;</b></a></legend><textarea id=tex rows='. count(explode("\n", $nfdebug)) .' style="font-family:\'Courier New\',Courier,monospace,Verdana, Arial, sans-serif;font-size:12px;width:100%;border:none;padding:0px;background:#000000;color:#ffffff;line-height:14px;';
			if ( (isset($_COOKIE['tex'])) && ($_COOKIE['tex'])==1) {echo 'display:none;'; }
			echo '" wrap="off">'. htmlentities( $nfdebug ) ."- stopping Firewall\n- processing time\t\t$bench s</textarea></fieldset></center><br>";
	   }
	}
}
