<?php

namespace Alexandr\Store\Model\ResourceModel;

use Alexandr\Store\Api\Data\StoreInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\DB\Select;


class Store extends AbstractDb
{
    /**
     * Name of entity table
     */
    public const TABLE_NAME = 'alexandr_store';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, StoreInterface::ID);
    }

    /**
     * @param string $url
     * @return string
     */
    public function checkUrlKey(string $url): string
    {
        $select = $this->loadByUrlKey($url);
        $select->reset(Select::COLUMNS)
            ->columns(StoreInterface::ID)
            ->limit(1);

        return $this->getConnection()->fetchOne($select);
    }

    /**
     * Check if url key is unique
     * @param string $url
     * @return bool
     */
    public function checkUniqueUrl($url): bool
    {
        $select = $this->getConnection()->select()
            ->from([self::TABLE_NAME])
            ->where(StoreInterface::STORE_URL_KEY . '= ?', $url);

        if ($this->getConnection()->fetchOne($select) == false) {
            return false; //url key is unique
        }
        return true; //url key is not unique
    }

    /**
     * @param string $url
     * @return Select
     */
    public function loadByUrlKey($url)
    {
        $select = $this->getConnection()->select()
            ->from([self::TABLE_NAME])
            ->where(StoreInterface::STORE_URL_KEY . '= ?', $url);

        return $select;
    }

}
