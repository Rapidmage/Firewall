<?php
 
namespace Rapidmage\Firewall\Setup;
 
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
 
class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
 
       
        $tableName = $installer->getTable('rapidmage_firewall');
        // Check if the table already exists
        if ($installer->getConnection()->isTableExists($tableName) != true) {
           
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'ID'
                )
                ->addColumn(
                    'ip_address',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Ip Address'
                )
                ->addColumn(
                    'description',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Description'
                )
                ->addColumn(
                    'req_count',
                    Table::TYPE_INTEGER,
                    null,
                    [ 
                        
                       ['nullable' => false, 'default' => 0],
                        ],
                    'Request Count'
                )
                 ->addColumn(
                    'flag',
                    Table::TYPE_INTEGER,
                    null,
                    [ 
                        
                        'nullable' => false
                        ],
                    'Request Count'
                )
                ->addColumn(
                    'edited_at',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Edited At'
                )
                ->addColumn(
                    'member_access',
                    Table::TYPE_SMALLINT,
                    null,
                    ['nullable' => false],
                    'Member Access'
                )
                ->setComment('Firewall access list')
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        }
 
        $installer->endSetup();
    }
}
