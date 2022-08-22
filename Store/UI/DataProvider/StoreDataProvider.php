<?php

declare(strict_types=1);

namespace Alexandr\Store\UI\DataProvider;

use Alexandr\Store\Model\ResourceModel\Store\Collection;
use Alexandr\Store\Model\ResourceModel\Store\CollectionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;
use Magento\Framework\Serialize\Serializer\Json;


class StoreDataProvider extends ModifierPoolDataProvider
{
    /**
     * @var Collection
     */
    protected $collection;
    /**
     * @var array
     */
    private $loadedData = [];
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var RequestInterface
     */
    private $request;

    private Json $json;
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param StoreManagerInterface $storeManager
     * @param RequestInterface $request
     * @param array $meta
     * @param array $data
     * @param Json $json
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        StoreManagerInterface $storeManager,
        RequestInterface $request,
        array $meta = [],
        array $data = [],
        Json $json
    ) {
        $this->collection = $collectionFactory->create();
        $this->storeManager = $storeManager;
        $this->request = $request;
        $this->json = $json;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @return array
     * @throws NoSuchEntityException
     */
    public function getData() :array
    {
        if (!empty($this->loadedData)) {
            return $this->loadedData;
        }
        $storeId = $this->request->getParam('store', 0);
        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        $items = $this->collection->getItems();
        foreach ($items as $store) {
            if ($store->getImage()) {
                $store->setImage([
                    [
                        'name' => $store->getImage(),
                        'url' => $mediaUrl . 'store/base_path/' . $store->getImage()
                    ]
                ]);
            }
            $name = $store->getName();
            if ($name == 'WARNING: No set name for this store view') {
                $store->setName("");
            } else {
                $store->setName($name);
            }

            if ($store->getSchedule()){
                $schedule = $this->json->unserialize($store->getSchedule());

                $store->setSchedule($schedule);
            }

            $this->loadedData[$store->getId()] = $store->getData();
        }

        return $this->loadedData;
    }
}
