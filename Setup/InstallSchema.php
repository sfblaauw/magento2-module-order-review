<?php

declare(strict_types = 1);

namespace Sfblaauw\OrderReview\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context): void
    {
        /** @var SchemaSetupInterface */
        $installer = $setup;

        $installer->startSetup();

        $installer
            ->getConnection()
            ->addColumn(
                $installer->getTable('sales_order'),
                'review_token',
                [
                    'type' => Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'default' => null,
                    'comment' => 'Review Token',
                ]
            );

        $installer
            ->getConnection()
            ->addColumn(
                $installer->getTable('sales_order'),
                'review_send',
                [
                    'type' => Table::TYPE_SMALLINT,
                    'nullable' => true,
                    'default' => 0,
                    'comment' => 'Review Send',
                ]
            );

        $reviewTable = $installer
            ->getConnection()
            ->newTable($installer->getTable('sfblaauw_order_review'))
            ->addColumn(
                'entity_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Review Id'
            )
            ->addColumn(
                'order_id',
                Table::TYPE_INTEGER,
                255,
                ['nullable' => false],
                'Order Id'
            )
            ->addColumn(
                'delivery',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => 0],
                'Delivery'
            )
            ->addColumn(
                'product',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => 0],
                'Product'
            )
            ->addColumn(
                'customer_service',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => 0],
                'Customer Service'
            )
            ->addColumn(
                'comment',
                Table::TYPE_TEXT,
                '2M',
                ['nullable' => true],
                'Comment'
            )
            ->addColumn(
                'status',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false, 'default' => 'pending'],
                'Status'
            )
            ->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Created At'
            )
            ->setComment('Sfblaauw Order Review')
            ->addIndex(
                $installer->getIdxName('sfblaauw_order_review', 'order_id', AdapterInterface::INDEX_TYPE_UNIQUE),
                'order_id',
                ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addForeignKey(
                $installer->getFkName('sfblaauw_order_review', 'entity_id', 'sales_order', 'entity_id'),
                'entity_id',
                $installer->getTable('sales_order'),
                'entity_id',
                Table::ACTION_CASCADE
            );

        $installer->getConnection()->createTable($reviewTable);
        $installer->endSetup();
    }
}
