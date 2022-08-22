<?php

namespace Alexandr\Store\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class ConfigProvider
{
    const XML_PATH_ENABLE = 'store/store/enable';
    const XML_PATH_GOOGLE_API_KEY = 'store/store/google_api_key';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return bool
     */
    public function isModuleEnable() :bool
    {
        $result = $this->scopeConfig->getValue(
            self::XML_PATH_ENABLE,
            ScopeInterface::SCOPE_STORE
        );
        return $result;
    }

    /**
     * @return string
     */
    public function getGoogleMapsApiKey(): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_GOOGLE_API_KEY,
            ScopeInterface::SCOPE_STORE
        );
    }
}
