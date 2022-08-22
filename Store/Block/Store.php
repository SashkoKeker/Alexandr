<?php

declare(strict_types=1);

namespace Alexandr\Store\Block;

use Alexandr\Store\Api\StoreRepositoryInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Serialize\Serializer\Json;
use Alexandr\Store\Model\ConfigProvider;

class Store extends Template
{
    /**
     * @var StoreRepositoryInterface
     */
    private StoreRepositoryInterface $storeRepository;
    /**
     * @var Context
     */
    private Context $context;
    /**
     * @var ConfigProvider
     */
    private ConfigProvider $configProvider;

    /**
     * @param Context $context
     * @param StoreRepositoryInterface $storeRepository
     * @param ConfigProvider $configProvider
     * @param Json $json
     */
    public function __construct(
        Context $context,
        StoreRepositoryInterface $storeRepository,
        ConfigProvider $configProvider,
        Json $json
    ) {
        $this->storeRepository = $storeRepository;
        $this->context = $context;
        $this->configProvider = $configProvider;
        $this->json = $json;
        parent::__construct($context);
    }

    /**
     * @return mixed|null
     */
    public function getStore()
    {
        $store = $this->getRequest()->getParams();
        if (is_null($store)) {
            return null;
        }
        return $store['store'];
    }

    /**
     * @return string
     */
    public function getGoogleApi()
    {
        $apiKey = $this->configProvider->getGoogleMapsApiKey();
        return $apiKey;
    }

    /**
     * @param $store
     * @return string|void
     */
    public function unserializeSchedule($store)
    {
        try {
            $schedule = $this->json->unserialize($store->getSchedule());
            foreach ($schedule as & $temp) {
                if (!isset($tmp)) {
                    $tmp = "day \"" . $temp['day'] . "\" - from \"" . $temp['from'] . "\" to \"" . $temp['to'] . "\", <br/> ";
                } else {
                    $tmp = $tmp . "day \"" . $temp['day'] . "\" - from \"" . $temp['from'] . "\" to \"" . $temp['to'] . "\" <br/>";
                }
            }
            return $tmp;
        } catch (\Exception $exception) {

        }
    }
}
