<?php

declare(strict_types=1);

namespace Alexandr\Store\Model;

use Alexandr\Store\Api\Data\StoreInterface;
use Alexandr\Store\Api\Data\StoreInterfaceFactory;
use Alexandr\Store\Api\Data\StoreSearchResultInterface;
use Alexandr\Store\Api\StoreRepositoryInterface;
use Alexandr\Store\Api\Data\StoreSearchResultInterfaceFactory;
use Alexandr\Store\Model\ResourceModel\Store as StoreResource;
use Alexandr\Store\Model\ResourceModel\Store\CollectionFactory;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Event\ManagerInterface as EventManager;

class StoreRepository implements StoreRepositoryInterface
{
    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;
    /**
     * @var StoreResource
     */
    private StoreResource $storeResource;
    /**
     * @var StoreInterfaceFactory
     */
    private $storeFactory;
    /**
     * @var SearchResultsInterfaceFactory
     */
    private $searchResultFactory;
    /**
     * @var EventManager
     */
    private $eventManager;
    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @param StoreFactory $storeFactory
     * @param CollectionFactory $collectionFactory
     * @param StoreResource $storeResource
     * @param StoreSearchResultInterfaceFactory $searchResultInterfaceFactory
     * @param EventManager $eventManager
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        StoreFactory $storeFactory,
        CollectionFactory $collectionFactory,
        StoreResource  $storeResource,
        StoreSearchResultInterfaceFactory $searchResultInterfaceFactory,
        EventManager $eventManager,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->storeFactory = $storeFactory;
        $this->collectionFactory = $collectionFactory;
        $this->storeResource = $storeResource;
        $this->searchResultFactory = $searchResultInterfaceFactory;
        $this->eventManager = $eventManager;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @param int $store_id
     * @return StoreInterface
     */
    public function getById(int $store_id): StoreInterface
    {
        $store = $this->storeFactory->create();
        $this->storeResource->load($store, $store_id);
        return $store;
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return StoreSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): StoreSearchResultInterface
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResult = $this->searchResultFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        return $searchResult;

    }

    /**
     * @param StoreInterface $store
     * @return StoreInterface
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function save(StoreInterface $store): StoreInterface
    {
        $this->eventManager->dispatch('store_store_save_before', ['store' => $store]);
        $this->storeResource->save($store);
        return $store;
    }

    /**
     * @param StoreInterface $store
     * @return void
     * @throws \Exception
     */
    public function delete(StoreInterface $store) : void
    {
        $this->storeResource->delete($store);
    }

    /**
     * @param int $store_id
     * @return void
     * @throws \Exception
     */
    public function deleteById(int $store_id): void
    {
        $store = $this->getById($store_id);
        $this->delete($store);
    }
}
