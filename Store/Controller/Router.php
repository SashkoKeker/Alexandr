<?php

namespace Alexandr\Store\Controller;


use Magento\Framework\App\Action\Forward;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ResponseInterface;


use Alexandr\Store\Model\ConfigProvider;
use Alexandr\Store\Api\Data\StoreInterfaceFactory;
use Alexandr\Store\Api\StoreRepositoryInterface;
use Alexandr\Store\Model\ResourceModel\Store as StoreResource;

class Router implements RouterInterface
{
    /**
     * @var ResponseInterface
     */
    private ResponseInterface $response;
    /**
     * @var ActionFactory
     */
    private ActionFactory $actionFactory;
    /**
     * @var StoreInterfaceFactory
     */
    private StoreInterfaceFactory $storeFactory;
    /**
     * @var StoreRepositoryInterface
     */
    private StoreRepositoryInterface $storeRepository;
    /**
     * @var StoreResource
     */
    private StoreResource $storeResource;

    /**
     * @param ActionFactory $actionFactory
     * @param ResponseInterface $response
     * @param StoreInterfaceFactory $storeInterfaceFactory
     * @param StoreRepositoryInterface $storeRepository
     * @param ConfigProvider $configProvider
     * @param StoreResource $storeResource
     */
    public function __construct(
        ActionFactory            $actionFactory,
        ResponseInterface        $response,
        StoreInterfaceFactory    $storeInterfaceFactory,
        StoreRepositoryInterface $storeRepository,
        ConfigProvider           $configProvider,
        StoreResource            $storeResource
    )
    {
        $this->storeRepository = $storeRepository;
        $this->storeFactory = $storeInterfaceFactory;
        $this->actionFactory = $actionFactory;
        $this->response = $response;
        $this->storeResource = $storeResource;
    }

    /**
     * @param RequestInterface $request
     * @return \Magento\Framework\App\ActionInterface
     */
    public function match(RequestInterface $request)
    {
        $urlKey = trim($request->getPathInfo(), '/');
        $url = explode('/', $urlKey);
        if (strpos($urlKey, 'front') !== false) {
            $request->setModuleName('front');
            $request->setControllerName('index');
            $request->setActionName('view');
            if(isset($url[1])) {
                $storeId = $this->storeResource->checkUrlKey($url[1]);

                if ($storeId !== "") {
                    $store = $this->storeRepository->getById($storeId);
                }
                $request->setParams([
                    'store' => $store,
                ]);
            }
            else {
                return $this->actionFactory->create(Forward::class);
            }
        }
        return $this->actionFactory->create(Forward::class, ['request' => $request]);
    }
}
