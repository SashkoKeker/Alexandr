<?php

namespace Alexandr\Store\Controller\Adminhtml\Store\Image;

use Magento\Catalog\Model\ImageUploader;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\File\UploaderFactory;
use Magento\Framework\View\FileSystem;
use Magento\Store\Model\StoreManagerInterface;
use Exception;

class Upload extends Action
{
    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;
    /**
     * @var FileSystem
     */
    protected FileSystem $fileSystem;
    /**
     * @var ImageUploader
     */
    protected ImageUploader $imageUploader;

    /**
     * @param Context $context
     * @param UploaderFactory $uploaderFactory
     * @param StoreManagerInterface $storeManager
     * @param FileSystem $fileSystem
     * @param ImageUploader $imageUploader
     */
    public function __construct(
        Context $context,
        UploaderFactory $uploaderFactory,
        StoreManagerInterface $storeManager,
        FileSystem $fileSystem,
        ImageUploader $imageUploader
    ) {
        $this->imageUploader = $imageUploader;
        $this->storeManager = $storeManager;
        $this->fileSystem = $fileSystem;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\Result\Json&\Magento\Framework\Controller\ResultInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $imageId = $this->_request->getParam('param_name', 'image');
        try {
            $result = $this->imageUploader->saveFileToTmpDir($imageId);
        } catch (Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}


