<?php

declare(strict_types = 1);

namespace Sfblaauw\OrderReview\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UninstallInterface;

class Uninstall implements UninstallInterface
{
    /**
     * {@inheritdoc}
     *
     * @todo Delete sales_order review_token and review_send column. Also remove config.
     */
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context): void
    {
        /** @var SchemaSetupInterface */
        $installer = $setup;

        $installer->getConnection()->dropTable($installer->getTable('sfblaauw_order_review'));
    }
}
