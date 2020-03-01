<?php

declare(strict_types = 1);

namespace Sfblaauw\OrderReview\Model\ResourceModel\Review;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Sfblaauw\OrderReview\Model\ResourceModel\Review as ReviewResourceModel;
use Sfblaauw\OrderReview\Model\Review as ReviewModel;

class Collection extends AbstractCollection
{
    /**
     * Resource initialization.
     */
    protected function _construct(): void
    {
        $this->_init(ReviewModel::class, ReviewResourceModel::class);
    }
}
