<?php

declare(strict_types = 1);

namespace Sfblaauw\OrderReview\Model;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Sfblaauw\OrderReview\Api\Data\ReviewInterface;
use Sfblaauw\OrderReview\Api\ReviewRepositoryInterface;
use Sfblaauw\OrderReview\Model\ResourceModel\Review as ReviewResource;
use Sfblaauw\OrderReview\Model\ReviewFactory;

class ReviewRepository implements ReviewRepositoryInterface
{
    /**
     * @var ReviewFactory
     */
    private $_reviewFactory;

    /**
     * @var ReviewResource
     */
    protected $_resource;

    /**
     * Constructor.
     *
     * @param ReviewFactory  $reviewFactory
     * @param ResourceSlider $resource
     */
    public function __construct(ReviewFactory $reviewFactory, ReviewResource $resource)
    {
        $this->_reviewFactory = $reviewFactory;
        $this->resource = $resource;
    }

    /**
     * {@inheritDoc}
     */
    public function save(ReviewInterface $review): ReviewInterface
    {
        try {
            $this->resource->save($review);
        } catch (\Throwable $e) {
            var_dump(get_class($e), $e->getMessage());die;

            throw new CouldNotSaveException(__('Could not save the review: %1', $e->getMessage()));
        }

        return $review;
    }

    /**
     * {@inheritDoc}
     */
    public function getById($reviewId): ReviewInterface
    {
        if (!$reviewId) {
            throw new InputException(__('An ID is needed. Set the ID and try again.'));
        }

        $review = $this->_reviewFactory->create();
        $review->load($reviewId);

        if (!$review->getId()) {
            throw new NoSuchEntityException(__('Requested review does not exist.'));
        }

        return $review;
    }

    /**
     * {@inheritDoc}
     */
    public function getByOrderId($orderId): ReviewInterface
    {
        if (!$orderId) {
            throw new InputException(__('An order ID is needed. Set the order ID and try again.'));
        }

        $review = $this->_reviewFactory->create();
        $review->load($orderId, 'order_id');

        if (!$review->getId()) {
            throw new NoSuchEntityException(__('Requested review does not exist.'));
        }

        return $review;
    }
}
