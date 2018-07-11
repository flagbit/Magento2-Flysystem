<?php
namespace Flagbit\Flysystem\Controller\Adminhtml\Filemanager;

use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Magento\Backend\App\Action;
use \Magento\Backend\Model\Session;
use \Magento\Backend\Model\View\Result\PageFactory;

/**
 * Class Index
 * @package Flagbit\Flysystem\Controller\Adminhtml\Flagbit\Flysystem
 */
class Index extends \Flagbit\Flysystem\Controller\Adminhtml\Filesystem\AbstractController
{
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * Index constructor.
     * @param Action\Context $context
     * @param Manager $flysystemManager
     * @param Session $session
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Action\Context $context,
        Manager $flysystemManager,
        Session $session,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context, $flysystemManager, $session);
        $this->_resultPageFactory = $resultPageFactory;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $this->_initAction();
        $this->getStorage()->setModalIdentifier('filemanager');
        $page = $this->_resultPageFactory->create()->addDefaultHandle();
        $page = $this->_initPage($page);
        return $page;
    }

    /**
     * @param \Magento\Backend\Model\View\Result\Page $resultPage
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initPage($resultPage)
    {
        $resultPage->setActiveMenu('Flagbit_Flysystem::flysystem_page')
            ->addBreadcrumb(__('Flagbit Flysystem'), __('Flagbit Flysystem'));
        $resultPage->getConfig()->getTitle()->prepend(__('Flagbit Flysystem'));
        return $resultPage;
    }
}