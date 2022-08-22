<?php

namespace Alexandr\Store\Controller\Adminhtml\Store;

use Alexandr\Store\Api\Data\StoreInterface;
use Alexandr\Store\Api\Data\StoreInterfaceFactory;
use Alexandr\Store\Api\StoreRepositoryInterface;
//use Alexandr\Store\Api\Data\StoreAttributeInterfaceFactory;
//use Alexandr\Store\Api\StoreAttributeRepositoryInterface;

use Magento\Catalog\Model\ImageUploader;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\RedirectFactory;
//use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\Serializer\Json;

class Save extends Action
{
    /**
     * @var RedirectFactory
     */
    private RedirectFactory $redirectFactory;
    /**
     * @var StoreInterfaceFactory
     */
    private StoreInterfaceFactory $storeFactory;
    /**
     * @var StoreRepositoryInterface
     */
    private StoreRepositoryInterface $storeRepository;
    /**
     * @var ImageUploader
     */
    private ImageUploader $imageUploader;

    private Json $json;

    /**
     * @param Context $context
     * @param RedirectFactory $redirectFactory
     * @param StoreInterfaceFactory $storeFactory
     * @param StoreRepositoryInterface $storeRepository
     * @param ImageUploader $imageUploader
     */
    public function __construct(
        Context $context,
        RedirectFactory $redirectFactory,
        StoreInterfaceFactory $storeFactory,
        StoreRepositoryInterface $storeRepository,
        ImageUploader $imageUploader,
        Json $json
    ) {
        parent::__construct($context);
        $this->storeFactory = $storeFactory;
        $this->storeRepository = $storeRepository;
        $this->imageUploader = $imageUploader;
        $this->redirectFactory = $redirectFactory;
        $this->json = $json;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $redirectResult = $this->redirectFactory->create();
        $store = $this->storeFactory->create();
        $data = $this->getRequest()->getPostValue();

        if (!$data['entity_id']) {
            $data['entity_id'] = null;
        } else {
            $store->setId($data['entity_id']);
        }

        $store->setName($data['name']);
        $store->setDescription($data['description']);
        $store->setAddress($data['address']);
        $store->setUrl($data['store_url_key']);
        $store = $this->setSchedule($data, $store);
        $store->setLatitude($data['latitude']);
        $store->setLongitude($data['longitude']);

        $store = $this->setImage($data, $store);
        $this->storeRepository->save($store);

        $redirectResult->setPath('*/*/index');
        return $redirectResult;
    }

    /**
     * @param $data
     * @param $store
     * @return StoreInterface
     */
    public function setSchedule($data, $store): StoreInterface
    {
        if (isset($data['schedule'])) {
            $store->setSchedule($this->json->serialize($data['schedule']));
        }
        return $store;
    }

    /**
     * @param $data
     * @param $store
     * @return StoreInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function setImage($data, $store): StoreInterface
    {
        if (isset($data['image'][0]['name']) && isset($data['image'][0]['tmp_name'])) {
            $data['image'] = $data['image'][0]['name'];
            $this->imageUploader->moveFileFromTmp($data['image']);
        } elseif (isset($data['image'][0]['name']) && !isset($data['image'][0]['tmp_name'])) {
            $data['image'] = $data['image'][0]['name'];
        } else {
            $data['image'] = '';
        }
        $store->setImage($data['image']);
        return $store;
    }
}
