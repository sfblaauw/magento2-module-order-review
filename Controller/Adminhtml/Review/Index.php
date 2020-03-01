<?php

declare(strict_types = 1);

namespace Sfblaauw\OrderReview\Controller\Adminhtml\Review;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    /**
     * @var PageFactory
     */
     protected $resultPageFactory;

     /**
      * Constructor.
      *
      * @param Context     $context
      * @param PageFactory $resultPageFactory
      */
     public function __construct(Context $context, PageFactory $resultPageFactory)
     {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
     }

     /**
      * {@inheritdoc}
      */
     public function execute()
     {
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Sfblaauw_OrderReview::order_review');
        $resultPage->getConfig()->getTitle()->prepend(__('Reviews'));

        return $resultPage;
     }
}
