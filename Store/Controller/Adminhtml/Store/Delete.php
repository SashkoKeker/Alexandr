<?php

namespace Alexandr\Store\Controller\Adminhtml\Store;


use Alexandr\Store\Api\Data\StoreInterfaceFactory;
use Alexandr\Store\Api\StoreRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\RedirectFactory;

class Delete extends Action
{
    /**
     * @var RedirectFactory
     */
    protected $resultFactory;
    /**
     * @var
     */
    protected $resultRedirectFactory;
    /**
     * @var StoreInterfaceFactory
     */
    protected $storeFactory;
    /**
     * @var StoreRepositoryInterface
     */
    protected $storeRepository;

    /**
     * @param Context $context
     * @param StoreInterfaceFactory $storeFactory
     * @param StoreRepositoryInterface $storeRepository
     * @param RedirectFactory $resultRedirectFactory
     */
    public function __construct(
        Context $context,
        StoreInterfaceFactory $storeFactory,
        StoreRepositoryInterface $storeRepository,
        RedirectFactory $resultRedirectFactory
    ) {
        $this->resultFactory = $resultRedirectFactory;
        $this->storeRepository = $storeRepository;
        $this->storeFactory = $storeFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('entity_id');
        $this->storeRepository->deleteById($id);
        $result = $this->resultRedirectFactory->create();
        return $result->setpath('store/store/index');
    }
}
