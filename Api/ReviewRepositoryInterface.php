<?php

declare(strict_types = 1);

namespace Sfblaauw\OrderReview\Api;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Sfblaauw\OrderReview\Api\Data\ReviewInterface;

interface ReviewRepositoryInterface
{
    /**
     * Saves a review.
     *
     * @param ReviewInterface $review
     *
     * @return ReviewInterface
     *
     * @throws CouldNotSaveException
     */
    public function save(ReviewInterface $review): ReviewInterface;

    /**
     * Get review by id.
     *
     * @param int $reviewId
     *
     * @return ReviewInterface
     *
     * @throws InputException
     * @throws NoSuchEntityException
     */
    public function getById($reviewId): ReviewInterface;

    /**
     * Get review by order id.
     *
     * @param int $orderId
     *
     * @return ReviewInterface
     *
     * @throws InputException
     * @throws NoSuchEntityException
     */
    public function getByOrderId($orderId): ReviewInterface;
}
