<?php
 
namespace Rapidmage\Firewall\Model\System\Config;
 
use Magento\Framework\Option\ArrayInterface;
 
class Status implements \Magento\Framework\Option\ArrayInterface
{
    const ENABLED  = 1;
    const DISABLED = 0;
 
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            self::ENABLED => __('Enabled'),
            self::DISABLED => __('Disabled')
        ];
 
        return $options;
    }
    public function toListArray()
    {
        $options = [
            self::ENABLED => __('Whitelist'),
            self::DISABLED => __('Blacklist')
        ];
 
        return $options;
    }
    public function toBlackListArray()
    {
        $options = [
            self::ENABLED => __('Whitelist'),
            self::DISABLED => __('Blacklist')
        ];
 
        return $options;
    }
    public function toWhiteListArray()
    {
        $options = [
            self::DISABLED => __('Blacklist'),
            self::ENABLED => __('Whitelist')
            
        ];
 
        return $options;
    }
}
