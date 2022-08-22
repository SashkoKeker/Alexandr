<?php

namespace Alexandr\Store\Model\ResourceModel\Store;

use Alexandr\Store\Model\Store;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Alexandr\Store\Model\ResourceModel\Store as StoreResourse;

class Collection extends AbstractCollection
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Store::class, StoreResourse::class);
    }
}
