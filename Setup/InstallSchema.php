<?php

namespace Oggetto\Entities\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $connection = $installer->getConnection();

        $table = $connection->newTable($installer->getTable('oggetto_entities'))
            ->addColumn('entity_id', Table::TYPE_INTEGER, null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true
                ], 'Entity ID')
            ->addColumn('name', Table::TYPE_TEXT, 255,
                [
                    'nullable' => false
                ], 'Name')
            ->setComment('Entity Table');

        $connection->createTable($table);

        $installer->endSetup();
    }
}
