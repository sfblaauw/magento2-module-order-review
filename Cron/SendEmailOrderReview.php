<?php

declare(strict_types = 1);

namespace Sfblaauw\OrderReview\Cron;

use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Sales\Model\ResourceModel\Order\Collection;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Sfblaauw\OrderReview\Helper\Data;

class SendEmailOrderReview
{
    /**
     * @var CollectionFactory
     */
    private $_orderCollectionFactory;

    /**
     * @var OrderRepositoryInterface
     */
    private $_orderRepository;

    /**
     * @var ScopeConfigInterface
     */
    private $_scopeConfig;

    /**
     * @var TransportBuilder
     */
    private $_transportBuilder;

    /**
     * @var StoreManagerInterface
     */
    private $_storeManager;

    /**
     * @var Data
     */
    private $_helper;

    /**
     * @var array
     */
    private $_storeCache = [];

    /**
     * Constructor.
     *
     * @param CollectionFactory        $orderCollectionFactory
     * @param OrderRepositoryInterface $orderRepository
     * @param ScopeConfigInterface   $scopeConfig
     * @param TransportBuilder      $transportBuilder
     * @param StoreManagerInterface $storeManager
     * @param Data                  $helper
     */
    public function __construct(
        CollectionFactory $orderCollectionFactory,
        OrderRepositoryInterface $orderRepository,
        ScopeConfigInterface $scopeConfig,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        Data $helper
    )
    {
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_orderRepository = $orderRepository;
        $this->_scopeConfig = $scopeConfig;
        $this->_transportBuilder = $transportBuilder;
        $this->_storeManager = $storeManager;
        $this->_helper = $helper;
    }

    /**
     * Sends the order review email message.
     */
    public function execute(): void
    {
        $orderCollection = $this->getOrderCollection();

        foreach ($orderCollection as $order) {
            try {
                $order->setReviewToken($this->generateToken());
                $order->setReviewSend(true);
                $this->_orderRepository->save($order);
                $this->sendReviewEmailMessage($order);
            } catch (\Throwable $e) {
                throw $e;

                continue;
            }
        }
    }

    /**
     * @return Collection
     */
    private function getOrderCollection(): Collection
    {
        $days = $this->_helper->getDays();
        $date = new \DateTime(sprintf('-%d days', $days));

        return $this->_orderCollectionFactory
            ->create()
            ->addFieldToFilter('review_send', false)
            ->addFieldToFilter('created_at', ['gteq' => $date->setTime(0, 0, 0)->format('Y-m-d H:i:s')])
            ->addFieldToFilter('created_at', ['lteq' => $date->setTime(23, 59, 59)->format('Y-m-d H:i:s')]);
    }

    /**
     * @param OrderInterface $order
     */
    private function sendReviewEmailMessage(OrderInterface $order): void
    {
        $senderEmail = $this->_scopeConfig->getValue('trans_email/ident_sales/email', ScopeInterface::SCOPE_STORE, $order->getStoreId());
        $senderName = $this->_scopeConfig->getValue('trans_email/ident_sales/name', ScopeInterface::SCOPE_STORE, $order->getStoreId());

        $customerEmail = $order->getCustomerEmail();
        $customerName = $order->getCustomerName();

        $transport = $this->_transportBuilder
            ->setTemplateIdentifier('sfblaauw_order_review_template')
            ->setTemplateOptions([
                'area' => Area::AREA_FRONTEND,
                'store' => Store::DEFAULT_STORE_ID,
            ])
            ->setTemplateVars(['orderReviewUrl' => $this->getReviewUrl($order)])
            ->setFrom([
                'email' => $senderEmail,
                'name' => $senderName,
            ])
            ->addTo($customerEmail, $customerName)
            ->getTransport();

        $transport->sendMessage();
    }

    /**
     * @return string
     */
    private function generateToken(): string
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }

    /**
     * @param OrderInterface $order
     *
     * @return string
     */
    private function getReviewUrl(OrderInterface $order): string
    {
        $store = $this->getStore($order->getStoreId());

        return sprintf('%s?token=%s', $store->getUrl('review'), $order->getReviewToken());
    }

    /**
     * @param int $storeId
     */
    private function getStore($storeId): StoreInterface
    {
        if (!isset($this->_storeCache[$storeId])) {
            $this->_storeCache[$storeId] = $this->_storeManager->getStore($storeId);
        }

        return $this->_storeCache[$storeId];
    }
}
