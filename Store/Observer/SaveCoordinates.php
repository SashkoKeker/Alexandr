<?php

namespace Alexandr\Store\Observer;

use Alexandr\Store\Api\GeoCoderInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class SaveCoordinates implements  ObserverInterface
{
    /**
     * @var GeoCoderInterface
     */
    private $geoCoder;

    /**
     * @param GeoCoderInterface $geoCoder
     */
    public function __construct(GeoCoderInterface $geoCoder)
    {
        $this->geoCoder = $geoCoder;
    }

    /**
     * @param Observer $observer
     * @return array|mixed|void|null
     */
    public function execute(Observer $observer)
    {
        $store = $observer->getData('store');
        $data = $store->getData();
        try {
            if ($data['latitude'] !== "" || $data['longitude'] !== "" ) {
                return $store;
            }
            else{
                throw new \Exception;
            }
        } catch (\Exception $exception) {
            $address = $store->getAddress();
            $coordinates = $this->geoCoder->getCoordinatesByAddress($address);
            $store->setLatitude($coordinates[1]);
            $store->setLongitude($coordinates[0]);
            return $store;
        }
    }
}
