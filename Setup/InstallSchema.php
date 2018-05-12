<?php

namespace SomethingDigital\AdminNotify\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface;

class InstallSchema implements InstallSchemaInterface
{
    const TABLE_NAME = 'sd_adminnotify_history';
    const UNIQUE_IDX_COLUMNS = [
        'user_id',
        'status',
        'ip',
    ];

    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;

        $installer->startSetup();

        $table = $installer->getConnection()->newTable(
            $installer->getTable(static::TABLE_NAME)
        )->addColumn(
            'history_id',
            Table::TYPE_INTEGER,
            10,
            [
                'unsigned' => true,
                'nullable' => false,
                'primary' => true,
                'identity' => true,
            ],
            'History record ID'
        )->addColumn(
            'user_id',
            Table::TYPE_INTEGER,
            10,
            [
                'unsigned' => true,
                'nullable' => false,
            ],
            'Admin user ID'
        )->addColumn(
            'ip',
            Table::TYPE_TEXT,
            45,
            [
                'nullable' => false
            ],
            'IP address'
        )->addColumn(
            'status',
            Table::TYPE_SMALLINT,
            5,
            [
                'unsigned' => true,
                'nullable' => false,
            ],
            'Login attempt status'
        )->addColumn(
            'attempts',
            Table::TYPE_INTEGER,
            10,
            [
                'unsigned' => true,
                'nullable' => false,
            ],
            'Login attempt count'
        )->addColumn(
            'created_at',
            Table::TYPE_TIMESTAMP,
            null,
            [
                'nullable' => false,
                'default' => '0000-00-00 00:00:00'
            ],
            'Creation time'
        )->addColumn(
            'updated_at',
            Table::TYPE_TIMESTAMP,
            null,
            [
                'nullable' => false,
            ],
            'Update time'
        );

        $adminUserTable = $installer->getTable('admin_user');

        $userIdFkName = $installer->getFkName(
            $adminUserTable,
            'user_id',
            static::TABLE_NAME,
            'user_id'
        );

        $table->addForeignKey(
            $userIdFkName,
            'user_id',
            $adminUserTable,
            'user_id',
            Table::ACTION_CASCADE,
            Table::ACTION_CASCADE
        );

        $table->addIndex(
            $installer->getIdxName(static::TABLE_NAME, static::UNIQUE_IDX_COLUMNS),
            static::UNIQUE_IDX_COLUMNS,
            [
                'type' => AdapterInterface::INDEX_TYPE_UNIQUE,
            ]
        );

        $table->setComment('Admin login history');

        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
