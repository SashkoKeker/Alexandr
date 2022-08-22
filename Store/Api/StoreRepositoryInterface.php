<?php

declare(strict_types=1);

namespace Alexandr\Store\Api;



use Alexandr\Store\Api\Data\StoreInterface;
use Alexandr\Store\Api\Data\StoreSearchResultInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface StoreRepositoryInterface
{
    /**
     * @param int $store_id
     * @return StoreInterface
     */
    public function getById(int $store_id): StoreInterface;

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return StoreSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): StoreSearchResultInterface;

    /**
     * @param StoreInterface $store
     * @return StoreInterface
     */
    public function save(StoreInterface $store): StoreInterface;

    /**
     * @param StoreInterface $workingHours
     * @return void
     */
    public function delete(StoreInterface $workingHours): void;

    /**
     * @param int $store_id
     * @return void
     */
    public function deleteById(int $store_id) : void;
}
