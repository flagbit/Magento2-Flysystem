<?php
namespace Flagbit\Flysystem\Controller\Adminhtml\Adaptermanager;

use \Magento\Backend\App\Action;
use \Magento\Backend\Model\View\Result\Page;
use \Magento\Framework\View\Result\PageFactory;

/**
 * Class Index
 * @package Flagbit\Flysystem\Controller\Adminhtml\Filemanager
 */
class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * Index constructor.
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
    }

    /**
     * @return Page|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $page = $this->_resultPageFactory->create()->addDefaultHandle();
        $page = $this->_initPage($page);
        return $page;
    }

    /**
     * @param Page|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page $resultPage
     * @return Page|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    protected function _initPage($resultPage)
    {
        $resultPage->setActiveMenu('Flagbit_Flysystem::flysystem_adapters')
            ->addBreadcrumb(__('Flagbit Flysystem'), __('Adapter Management'));
        $resultPage->getConfig()->getTitle()->prepend(__('Flagbit Flysystem Adapter Management'));
        return $resultPage;
    }
}