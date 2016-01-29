<?php
 
namespace Rapidmage\Firewall\Model\ResourceModel\Whiteip;
 
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
 
class Collection extends AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'Rapidmage\Firewall\Model\Whiteip',
            'Rapidmage\Firewall\Model\ResourceModel\Whiteip'
        );
    }
}
