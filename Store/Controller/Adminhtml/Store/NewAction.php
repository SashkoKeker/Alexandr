<?php

namespace Alexandr\Store\Controller\Adminhtml\Store;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\App\Action\HttpGetActionInterface;

/**
 * Create store action
 */
class NewAction extends Action implements HttpGetActionInterface
{
    /**
     * @var ForwardFactory
     */
    protected $forwardFactory;

    /**
     * @param Action\Context $context
     * @param ForwardFactory $forwardFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        ForwardFactory $forwardFactory
    ) {
        $this->forwardFactory = $forwardFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Forward|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $result = $this->forwardFactory->create();
        $result->forward('edit');
        return $result;
    }
}
