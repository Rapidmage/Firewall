<?php
 
namespace Rapidmage\Firewall\Model\ResourceModel\Blackip;
 
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
 
class Collection extends AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'Rapidmage\Firewall\Model\Blackip',
            'Rapidmage\Firewall\Model\ResourceModel\Blackip'
        );
    }
}
