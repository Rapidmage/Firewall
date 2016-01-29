<?php
 
namespace Rapidmage\Firewall\Model\ResourceModel\Ip;
 
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
 
class Collection extends AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'Rapidmage\Firewall\Model\Ip',
            'Rapidmage\Firewall\Model\ResourceModel\Ip'
        );
    }
}
