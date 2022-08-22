<?php

namespace Alexandr\Store\Model\Source;

use Alexandr\Store\Api\GeoCoderInterface;
use Alexandr\Store\Model\ConfigProvider;
use Zend_Http_Client;
use Magento\Framework\Exception\CouldNotSaveException;

class GeoCoder implements GeoCoderInterface
{

    const GOOGLE_MAPS_HOST = 'https://maps.googleapis.com/maps/api/geocode/';
    /**
     * @var
     */
    private $configProvider;
    /**
     * @var Zend_Http_Client
     */
    protected $client;


    /**
     * @param ConfigProvider $configProvider
     */
    public function __construct(
        ConfigProvider $configProvider,
        Zend_Http_Client $client
    ) {
        $this->configProvider = $configProvider;
        $this->client = $client;
    }

    /**
     * @param string $address
     * @return array
     * @throws CouldNotSaveException
     */
    public function getCoordinatesByAddress(string $address)
    {
        $apiKey = $this->configProvider->getGoogleMapsApiKey();

        try {
            $this->client->setUri(self::GOOGLE_MAPS_HOST . 'json');
            $this->client->setMethod(Zend_Http_Client::GET);
            $this->client->setParameterGet('address', $address);
            $this->client->setParameterGet('key', $this->configProvider->getGoogleMapsApiKey());
            $response = $this->client->request();
        } catch (\Zend_Http_Client_Exception $e) {
            throw new CouldNotSaveException(__('Request error'));
        }
        if ($response->isSuccessful() && $response->getStatus() == 200) {
            try {
                $response = json_decode($response->getBody())->results[0];
                $location = $response->geometry->location;
                $coordinates[0] = $location->lat;
                $coordinates[1] = $location->lng;
                return $coordinates;
            } catch (\Exception $e) {
                throw new CouldNotSaveException(__('Invalid address'));
            }
        } else {
            throw new CouldNotSaveException(__('Google Maps API error'));
        }

//        $geo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false&key='.$apiKey);
//        if (strpos($geo, 'error_message')) {
//            $coordinates = 'ErrorApi';
//        } elseif (strpos($geo, 'ZERO_RESULTS')) {
//            $coordinates = 'ZERO_RESULTS';
//        } else {
//            $geo = json_decode($geo, true);
//            if (isset($geo['status']) && ($geo['status'] == 'OK')) {
//                $coordinates[0] = $geo['results'][0]['geometry']['location']['lat'];
//                $coordinates[1] = $geo['results'][0]['geometry']['location']['lng'];
//            }
//        }
//        return $coordinates;
    }
}
