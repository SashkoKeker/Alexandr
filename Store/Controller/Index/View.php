<?php

namespace Alexandr\Store\Controller\Index;

use Alexandr\Store\Api\Data\StoreInterfaceFactory;
use Alexandr\Store\Api\StoreRepositoryInterface;
use Alexandr\Store\Model\ConfigProvider;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\RequestInterface;

class View implements  HttpGetActionInterface
{

    private $pageFactory;
    private $request;
    private $storeFactory;
    private $storeRepository;
    private $configProvider;
    private $redirectFactory;

    /**
     * @param PageFactory $pageFactory
     * @param RequestInterface $request
     * @param StoreInterfaceFactory $storeFactory
     * @param StoreRepositoryInterface $storeRepository
     * @param ConfigProvider $configProvider
     * @param RedirectFactory $redirectFactory
     */
    public function __construct(
        PageFactory $pageFactory,
        RequestInterface $request,
        StoreInterfaceFactory $storeFactory,
        StoreRepositoryInterface $storeRepository,
        ConfigProvider $configProvider,
        RedirectFactory $redirectFactory
    ) {
        $this->redirectFactory = $redirectFactory;
        $this->configProvider = $configProvider;
        $this->storeRepository = $storeRepository;
        $this->storeFactory = $storeFactory;
        $this->pageFactory = $pageFactory;
        $this->request = $request;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $page =  $this->pageFactory->create();
        if ($this->configProvider->isModuleEnable() ==  false) {
            return $this->redirectFactory->create()->setUrl('/');
        }
        $store = $this->request->getParam('store');
        if (!$store) {
            return $this->redirectFactory->create()->setPath('/');
        }
        if ($store->getData() == null) {
            $page->getConfig()->getTitle()->prepend('no such store');
            return $page;
        }
        $name = $store->getName();
        $page->setHeader('name', $name);
        $page->getConfig()->getTitle()->prepend($name);
        return $page;
    }
}
