<?php

namespace Alexandr\Store\Controller\Adminhtml\Store;



use Alexandr\Store\Api\Data\StoreInterfaceFactory;
use Alexandr\Store\Api\StoreRepositoryInterface;
use Alexandr\Store\Model\StoreRepository;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;

class Edit extends Action
{
    const ADMIN_RESOURCE = "Alexandr::Store";
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var StoreInterfaceFactory
     */
    protected $storeFactory;
    /**
     * @var StoreRepositoryInterface
     */
    protected $storeRepository;
    /**
     * @var
     */
    protected $dataProvider;
    /**
     * @var
     */
    protected $coreRegistry;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param Context $context
     * @param StoreInterfaceFactory $storeInterfaceFactory
     * @param StoreRepositoryInterface $storeRepository
     * @param StoreManagerInterface $storeManager
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        StoreInterfaceFactory $storeInterfaceFactory,
        StoreRepositoryInterface $storeRepository,
        StoreManagerInterface $storeManager,
        PageFactory $resultPageFactory
    )
    {

        parent::__construct($context);
        $this->storeRepository = $storeRepository;
        $this->storeFactory = $storeInterfaceFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * @return ResponseInterface|\Magento\Framework\Controller\Result\Redirect|ResultInterface|\Magento\Framework\View\Result\Page
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
//        $storeViewId = $this->getRequest()->getParam('store', 0);
//        $storeView = $this->storeManager->getStore($storeViewId);
//        $this->storeManager->setCurrentStore($storeView->getCode());

        $id = $this->getRequest()->getParam('entity_id');
        if ($id) {
            $store = $this->storeRepository->getById($id);
            if (!$store->getId()) {
                //$this->messageManager->addErrorMessage(__('No store with that id'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Alexandr_Store::menu_1');
        $resultPage->getConfig()->getTitle()->prepend(__('Store'));
        $resultPage->addHandle('store_store_entity' . $id);
        return $resultPage;
    }
}



