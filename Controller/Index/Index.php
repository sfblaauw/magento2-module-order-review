<?php

declare(strict_types = 1);

namespace Sfblaauw\OrderReview\Controller\Index;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use Sfblaauw\OrderReview\Api\ReviewRepositoryInterface;
use Sfblaauw\OrderReview\Block\Index as BlockIndex;
use Sfblaauw\OrderReview\Controller\Review as ReviewAction;

class Index extends ReviewAction
{
    /**
     * @var PageFactory
     */
    private $_pageFactory;

    /**
     * Constructor.
     *
     * @param Context                   $context
     * @param PageFactory               $pageFactory
     * @param OrderRepositoryInterface  $orderRepository
     * @param SearchCriteriaBuilder     $searchCriteriaBuilder
     * @param ReviewRepositoryInterface $reviewRepository
     */
	public function __construct(
        Context $context,
        PageFactory $pageFactory,
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ReviewRepositoryInterface $reviewRepository
    )
	{
        $this->_pageFactory = $pageFactory;
		parent::__construct($context, $orderRepository, $searchCriteriaBuilder, $reviewRepository);
    }

    /**
     * {@inheritDoc}
     */
	public function execute()
	{
        try {
            $order = $this->getOrderByReviewToken($this->getRequestToken());
        } catch (NoSuchEntityException $e) {
            return $this->_redirect('/');
        }

        $page = $this->_pageFactory->create();

        /** @var BlockIndex */
        $block = $page->getLayout()->getBlock('review_index_index');
        $block->setOrder($order);

		return $page;
	}
}
