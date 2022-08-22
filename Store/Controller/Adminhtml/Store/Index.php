<?php

namespace Alexandr\Store\Controller\Adminhtml\Store;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;


class Index extends Action
{

    const ADMIN_RESOURCE = 'Magento_Backend::system';

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Alexandr_Store::menu_1');
        //    ->addBreadcrumb(__('Store'), __('List'));
        $resultPage->getConfig()->getTitle()->prepend(__('Store locator'));

        return $resultPage;

    }
}
