<?php

declare(strict_types = 1);

namespace Sfblaauw\OrderReview\Block;

use Magento\Framework\View\Element\Template;
use Magento\Sales\Api\Data\OrderInterface;

class Index extends Template
{
    /**
     * @var OrderInterface
     */
    private $_order;

    /**
     * Set order.
     *
     * @param OrderInterface $order
     */
    public function setOrder(OrderInterface $order): void
    {
        $this->_order = $order;
    }

    /**
     * Get order.
     *
     * @return OrderInterface|null
     */
    public function getOrder(): ?OrderInterface
    {
        return $this->_order;
    }

    /**
     * @return string
     */
    public function getSaveUrl(): string
    {
        return sprintf('%s?token=%s', $this->getUrl('review/save'), $this->_order->getReviewToken());
    }
}
