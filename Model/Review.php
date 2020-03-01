<?php

declare(strict_types = 1);

namespace Sfblaauw\OrderReview\Model;

use Magento\Framework\Model\AbstractModel;
use Sfblaauw\OrderReview\Api\Data\ReviewInterface;

class Review extends AbstractModel implements ReviewInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'sfblaauw_order_review';

    /**
     * @var string
     */
    protected $_eventObject = 'review';

    /**
     * {@inheritDoc}
     */
    public function setEntityId($entityId): self
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * {@inheritDoc}
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * {@inheritDoc}
     */
    public function setOrderId($orderId): self
    {
        return $this->setData(self::ORDER_ID, $orderId);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrderId()
    {
        return $this->getData(self::ORDER_ID);
    }

    /**
     * {@inheritDoc}
     */
    public function setDelivery($delivery): self
    {
        return $this->setData(self::DELIVERY, $delivery);
    }

    /**
     * {@inheritDoc}
     */
    public function getDelivery()
    {
        return $this->getData(self::DELIVERY);
    }

    /**
     * {@inheritDoc}
     */
    public function setProduct($product): self
    {
        return $this->setData(self::PRODUCT, $product);
    }

    /**
     * {@inheritDoc}
     */
    public function getProduct()
    {
        return $this->getData(self::PRODUCT);
    }

    /**
     * {@inheritDoc}
     */
    public function setCustomerService($customerService): self
    {
        return $this->setData(self::CUSTOMER_SERVICE, $customerService);
    }

    /**
     * {@inheritDoc}
     */
    public function getCustomerService()
    {
        return $this->getData(self::CUSTOMER_SERVICE);
    }

    /**
     * {@inheritDoc}
     */
    public function setComment($comment): self
    {
        return $this->setData(self::COMMENT, $comment);
    }

    /**
     * {@inheritDoc}
     */
    public function getComment()
    {
        return $this->getData(self::COMMENT);
    }

    /**
     * {@inheritDoc}
     */
    public function setStatus($status): self
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * {@inheritDoc}
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * {@inheritDoc}
     */
    public function setCreatedAt($createdAt): self
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * {@inheritDoc}
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct(): void
    {
        $this->_init('Sfblaauw\OrderReview\Model\ResourceModel\Review');
    }
}
