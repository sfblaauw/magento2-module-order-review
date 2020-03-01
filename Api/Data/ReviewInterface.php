<?php

declare(strict_types = 1);

namespace Sfblaauw\OrderReview\Api\Data;

interface ReviewInterface
{
    const ENTITY_ID = 'entity_id';
    const ORDER_ID = 'order_id';
    const DELIVERY = 'delivery';
    const PRODUCT = 'product';
    const CUSTOMER_SERVICE = 'customer_service';
    const COMMENT = 'comment';
    const STATUS = 'status';
    const CREATED_AT = 'created_at';

    /**
     * Set entity ID.
     *
     * @param int $entityId
     *
     * @return $this
     */
    public function setEntityId($entityId);

    /**
     * Get entity ID.
     *
     * @return int|null
     */
    public function getEntityId();

    /**
     * Set order ID.
     *
     * @param int $orderId
     *
     * @return $this
     */
    public function setOrderId($orderId);

    /**
     * Get order ID.
     *
     * @return int|null
     */
    public function getOrderId();

    /**
     * Set delivery.
     *
     * @param int $delivery
     *
     * @return $this
     */
    public function setDelivery($delivery);

    /**
     * Get delivery.
     *
     * @return int|null
     */
    public function getDelivery();

    /**
     * Set product.
     *
     * @param int $product
     *
     * @return $this
     */
    public function setProduct($product);

    /**
     * Get product.
     *
     * @return int
     */
    public function getProduct();

    /**
     * Set customer service.
     *
     * @param int $customerService
     *
     * @return $this
     */
    public function setCustomerService($customerService);

    /**
     * Get customer service.
     *
     * @return int
     */
    public function getCustomerService();

    /**
     * Set comment.
     *
     * @param string|null $comment
     *
     * @return $this
     */
    public function setComment($comment);

    /**
     * Get comment.
     *
     * @return string|null
     */
    public function getComment();

    /**
     * Set status.
     *
     * @param string $status
     *
     * @return $this
     */
    public function setStatus($status);

    /**
     * Get status.
     *
     * @return string|null
     */
    public function getStatus();

    /**
     * Set created at.
     *
     * @param string $createdAt
     *
     * @return $this
     */
    public function setCreatedAt($createdAt);

    /**
     * Get created at.
     *
     * @return string|null
     */
    public function getCreatedAt();
}
