<?php

namespace Alexandr\Store\UI\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Alexandr\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;
class Actions extends Column
{
    /**
     * Edit action path
     */
    const URL_PATH_EDIT = 'store/store/edit';
    /**
     * Delete action path
     */
    const URL_PATH_DELETE = 'store/store/delete';

    const URL_PATH_FRONT = 'front';
    /**
     * @var UrlInterface
     */
    private $urlBuilder;
    /**
     * @var ContextInterface
     */
    private $storeManager;
    protected $context;
    /**
     * @var UiComponentFactory
     */
    protected $uiComponentFactory;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = [],
        StoreManagerInterface $storeManager
    ) {
        $this->context = $context;
        $this->uiComponentFactory = $uiComponentFactory;
        $this->urlBuilder = $urlBuilder;
        $this->storeManager = $storeManager;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @param array $dataSource
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $storeId = $this->getData('store_entity_id');
        }
        foreach ($dataSource['data']['items'] as &$item) {
            $item[$this->getData('name')]['edit'] = [
                'href' => $this->urlBuilder->getUrl(
                    self::URL_PATH_EDIT,
                    [StoreInterface::ID => $item[StoreInterface::ID]]
                ),
                'label' => __('Edit'),
                'hidden' => false,
            ];
            $item[$this->getData('name')]['delete'] = [
                'href' => $this->urlBuilder->getUrl(
                    self::URL_PATH_DELETE,
                    [StoreInterface::ID => $item[StoreInterface::ID]]
                ),
                'label' => __('Delete'),
                'hidden' => false,
            ];
            $item[$this->getData('name')]['front'] = [
                'href' => $this->storeManager->getDefaultStoreView()->getUrl() . self::URL_PATH_FRONT . '/' . $item['store_url_key'],
                'label' => __('Front'),
                'hidden' => false,
            ];
        }
        return $dataSource;
    }
}
