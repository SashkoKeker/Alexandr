<?php

namespace Alexandr\Store\Api\Data;

use \Magento\Framework\Api\SearchResultsInterface;

interface StoreSearchResultInterface extends SearchResultsInterface
{
    /**
     * @return \Alexandr\Store\Api\Data\StoreInterface[]
     */
    public function getItems();

    /**
     * @param \Alexandr\Store\Api\Data\StoreInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
