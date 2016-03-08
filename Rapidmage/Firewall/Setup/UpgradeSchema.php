<?php 
namespace Rapidmage\Firewall\Setup;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
class UpgradeSchema implements  UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup,
                            ModuleContextInterface $context){
				
				$installer = $setup;				
           $setup->startSetup();
     
            $tableName = $setup->getTable('rapidmage_rules');

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
                    'who',
                    Table::TYPE_TEXT,
                    '2M',
                    ['nullable' => false, 'default' => ''],
                    'Who'
                )
                ->addColumn(
                    'request',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Request'
                )
                ->addColumn(
                    'what',
                    Table::TYPE_TEXT,
                    null,
                    [ 
                        
                       ['nullable' => false, 'default' => 0],
                        ],
                    'What'
                )
                 ->addColumn(
                    'why',
                    Table::TYPE_TEXT,
                    null,
                    [ 
                        
                        'nullable' => false
                        ],
                    'Why'
                )
                ->addColumn
                (
                    'level',
                    Table::TYPE_TEXT,
                    null,
                    [ 
                        
                        'nullable' => false
                        ],
                    'Level'
                )
                 ->addColumn(
                    'enabled',
                     Table::TYPE_INTEGER,
                    null,
                    [ 
                        
                        'nullable' => false
                        ],
                    'Enabled'
                )
                ->addColumn(
                    'edited_at',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Edited At'
                )
                ->addColumn(
                    'updated_at',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Updated At'
                )
                
                ->setComment('Firewall Rules list')
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        }

        $setup->endSetup();
    }
}
