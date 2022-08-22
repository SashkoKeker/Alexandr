<?php

namespace Alexandr\Store\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Alexandr\Store\Model\ResourceModel\Store as StoreResource;

class SaveUrl implements  ObserverInterface
{

    /**
     * @var StoreResource
     */
    private $storeResource;

    /**
     * @param StoreResource $storeResource
     */
    public function __construct(StoreResource $storeResource)
    {
        $this->storeResource = $storeResource;
    }

    /**
     * @param Observer $observer
     * @return array|mixed|void|null
     * @throws \Exception
     */
    public function execute(Observer $observer)
    {
        $store = $observer->getData('store');
        $data = $store->getData();
        if (!array_key_exists('name', $data)) {
            return $store;
        }
        try {
            $url = $data['store_url_key'];
            if ($url !== "") {
                if ($this->storeResource->checkUniqueUrl($url) == true) {
                    return $store;
                }
            } else {
                throw new \Exception();
            }
        } catch (\Exception $exception) {
            $name = str_replace(' ', '-', strtolower($data['name']));
            if ($this->storeResource->checkUniqueUrl($name) == false) {
                $store->setUrl($name);
            } else {
                $store->setUrl($name . '-' . random_int(0, 100));
            }
        }
        return $store;
    }
}
