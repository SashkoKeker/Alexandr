<?php

namespace Alexandr\Store\Plugin;

use Alexandr\Store\Api\Data\StoreInterface;
use Alexandr\Store\Model\StoreRepository;
use Alexandr\Store\Model\ResourceModel\Store as StoreResource;

class UrlGeneration
{
    /**
     * @var StoreResource
     */
    private StoreResource $storeResource;

    public function __construct(
        StoreResource $storeResource
    ) {
        $this->storeResource = $storeResource;
    }

    /**
     * @param StoreRepository $subject
     * @param StoreInterface $store
     * @return StoreInterface[]
     * @throws \Exception
     */
    public function beforeSave(StoreRepository $subject, StoreInterface $store): array
    {
        $data = $store->getData();
        if (!array_key_exists('name', $data)) {
            return [$store];
        }
        try {
            $url = $data['store_url_key'];
            if ($url !== "") {
                if ($this->storeResource->checkUniqueUrl($url) == true) {
                    return [$store];
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
        return [$store];
    }
}
