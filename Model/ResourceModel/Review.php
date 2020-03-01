<?php

declare(strict_types = 1);

namespace Sfblaauw\OrderReview\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Review extends AbstractDb
{
    /**
     * {@inheritdoc}
     */
    protected function _construct(): void
    {
        $this->_init('sfblaauw_order_review', 'entity_id');
    }
}
