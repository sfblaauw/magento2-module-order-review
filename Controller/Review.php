<?php

declare(strict_types = 1);

namespace Sfblaauw\OrderReview\Controller;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Sfblaauw\OrderReview\Api\ReviewRepositoryInterface;

abstract class Review extends Action
{
    /**
     * @var OrderRepositoryInterface
     */
    protected $_orderRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $_searchCriteriaBuilder;

    /**
     * @var ReviewRepositoryInterface
     */
    protected $_reviewRepository;

    /**
     * Constructor.
     *
     * @param Context                   $context
     * @param OrderRepositoryInterface  $orderRepository
     * @param SearchCriteriaBuilder     $searchCriteriaBuilder
     * @param ReviewRepositoryInterface $reviewRepository
     */
	public function __construct(
        Context $context,
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ReviewRepositoryInterface $reviewRepository
    )
	{
        $this->_orderRepository = $orderRepository;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->_reviewRepository = $reviewRepository;
		parent::__construct($context);
    }

    /**
     * Get order by review token.
     *
     * @param string|null $token
     *
     * @return OrderInterface
     *
     * @throws NoSuchEntityException
     */
    protected function getOrderByReviewToken(?string $token): OrderInterface
    {
        $this->_searchCriteriaBuilder->addFilter('review_token', $token);

        $orderCollection = $this->_orderRepository->getList($this->_searchCriteriaBuilder->create());

        if (!$orderCollection->getTotalCount() || $orderCollection->getTotalCount() > 1) {
            throw new NoSuchEntityException(__('Unable to find order with token "%s".', $token));
        }

        return current($orderCollection->getItems());
    }

    /**
     * Get request token.
     *
     * @return string|null
     */
    protected function getRequestToken(): ?string
    {
        return $this->_request->getParam('token');
    }
}
