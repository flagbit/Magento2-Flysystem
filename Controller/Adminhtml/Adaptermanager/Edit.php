<?php
namespace Flagbit\Flysystem\Controller\Adminhtml\Adaptermanager;

use \Flagbit\Flysystem\Model\AdapterProviderFactory;
use \Flagbit\Flysystem\Model\AdapterProviderRepository;
use \Magento\Backend\App\Action;
use \Magento\Backend\Model\View\Result\Page;
use \Magento\Framework\View\Result\PageFactory;

/**
 * Class Index
 * @package Flagbit\Flysystem\Controller\Adminhtml\Filemanager
 */
class Edit extends Action
{
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var AdapterProviderFactory
     */
    protected $_adapterProviderFactory;

    /**
     * @var AdapterProviderRepository
     */
    protected $_adapterProviderRepository;

    /**
     * Index constructor.
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        AdapterProviderFactory $adapterProviderFactory,
        AdapterProviderRepository $adapterProviderRepository
    ) {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
        $this->_adapterProviderFactory = $adapterProviderFactory;
        $this->_adapterProviderRepository = $adapterProviderRepository;
    }

    /**
     * @return Page|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        $adapter = $this->_initAdapter();

        if(!$adapter) {
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('flagbit_flysystem/index', ['_current' => true, 'id' => null]);
        }

        $page = $this->_resultPageFactory->create()->addDefaultHandle();
        $page = $this->_initPage($page);
        return $page;
    }

    /**
     * @param null $adapterId
     * @return \Flagbit\Flysystem\Api\Data\AdapterProviderInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function _initAdapter($adapterId  = null)
    {
        $adapter = $this->_adapterProviderFactory->create();
        if (empty($adapterId)) {
            $adapterId = (int) $this->getRequest()->getParam('adapter_id', false);
        }

        if ($adapterId) {
            $adapter = $this->_adapterProviderRepository->get($adapterId);
        }

        return $adapter;
    }

    /**
     * @param Page|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page $resultPage
     * @return Page|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    protected function _initPage($resultPage)
    {
        $resultPage->setActiveMenu('Flagbit_Flysystem::flysystem_adapters')
            ->addBreadcrumb(__('Flagbit Flysystem'), __('New Adapter Provider'));
        $resultPage->getConfig()->getTitle()->prepend(__('New Adapter Provider'));
        return $resultPage;
    }
}