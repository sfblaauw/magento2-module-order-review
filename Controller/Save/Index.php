<?php

declare(strict_types = 1);

namespace Sfblaauw\OrderReview\Controller\Save;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\OrderRepositoryInterface;
use Sfblaauw\OrderReview\Api\Data\ReviewInterface;
use Sfblaauw\OrderReview\Api\ReviewRepositoryInterface;
use Sfblaauw\OrderReview\Controller\Review as ReviewAction;
use Sfblaauw\OrderReview\Model\ReviewFactory;

class Index extends ReviewAction
{
    /**
     * @var ReviewFactory
     */
    private $_reviewFactory;

    /**
     * Constructor.
     *
     * @param Context                   $context
     * @param OrderRepositoryInterface  $orderRepository
     * @param SearchCriteriaBuilder     $searchCriteriaBuilder
     * @param ReviewFactory             $reviewFactory
     * @param ReviewRepositoryInterface $reviewRepository
     */
	public function __construct(
        Context $context,
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ReviewFactory $reviewFactory,
        ReviewRepositoryInterface $reviewRepository
    )
    {
        $this->_reviewFactory = $reviewFactory;
		parent::__construct($context, $orderRepository, $searchCriteriaBuilder, $reviewRepository);
    }

    /**
     * {@inheritDoc}
     */
	public function execute(): ResponseInterface
	{
        if (!$this->_request->isPost()) {
            return $this->_redirect('/');
        }

        try {
            $order = $this->getOrderByReviewToken($this->getRequestToken());
        } catch (NoSuchEntityException $e) {
            return $this->_redirect('/');
        }

        try {
            $this->_reviewRepository->getByOrderId($order->getEntityId());
            $this->messageManager->addNotice(__('The review alredy exists.'));

            return $this->_redirect('/');
        } catch (NoSuchEntityException $e) {
            // the review does not exists
        }

        if (!$this->isValidForm()) {
            $this->messageManager->addError(__('The form is invalid.'));

            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        $data = $this->_request->getPost();
        /** @var ReviewInterface */
        $review = $this->_reviewFactory->create();
        $review
            ->setOrderId($order->getEntityId())
            ->setDelivery($data['delivery'])
            ->setProduct($data['product'])
            ->setCustomerService($data['customer_service'])
            ->setComment(strip_tags($data['comment']));

        try {
            $order->setReviewToken(null);
            $this->_orderRepository->save($order);
            $this->_reviewRepository->save($review);
        } catch (\Throwable $e) {
            $this->messageManager->addError('Ups... has been an error. Pleas try again.');

            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        $this->messageManager->addSuccess('Thanks! Your review has been saved.');

        return $this->_redirect('/');
    }

    /**
     * Check if the form is valid.
     *
     * @return bool
     */
    private function isValidForm(): bool
    {
        foreach (['delivery', 'product', 'customer_service'] as $name) {
            $value = $this->_request->getPost($name);
            if ($value < 0 || $value > 5) {
                return false;
            }
        }

        return true;
    }
}
