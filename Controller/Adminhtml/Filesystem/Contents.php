<?php
namespace Flagbit\Flysystem\Controller\Adminhtml\Filesystem;

use \Flagbit\Flysystem\Helper\Filesystem;
use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\Controller\Result\JsonFactory;
use \Magento\Framework\View\Result\LayoutFactory;
use \Magento\Backend\Model\Session;

/**
 * Class Contents
 * @package Flagbit\Flysystem\Controller\Adminhtml\Filesystem
 */
class Contents extends AbstractController
{
    /**
     * @var LayoutFactory
     */
    protected $_resultLayoutFactory;

    /**
     * @var JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * Contents constructor.
     * @param Context $context
     * @param Manager $flysystemManager
     * @param Session $session
     * @param LayoutFactory $resultLayoutFactory
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        Manager $flysystemManager,
        Session $session,
        LayoutFactory $resultLayoutFactory,
        JsonFactory $resultJsonFactory
    ) {
        $this->_resultLayoutFactory = $resultLayoutFactory;
        $this->_resultJsonFactory = $resultJsonFactory;
        parent::__construct($context, $flysystemManager, $session);
    }

    /**
     * @return Contents
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _saveSessionCurrentPath(): self
    {
        $this->getStorage()->getSession()->setCurrentPath(
            $this->_objectManager->get(Filesystem::class)->getCurrentPath()
        );
        return $this;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Layout
     */
    public function execute()
    {
        try {
            $this->_initAction()->_saveSessionCurrentPath();
            /** @var \Magento\Framework\View\Result\Layout $resultLayout */
            $resultLayout = $this->_resultLayoutFactory->create();
            return $resultLayout;
        } catch (\Exception $e) {
            $result = ['error' => true, 'message' => $e->getMessage()];
            /** @var \Magento\Framework\Controller\Result\Json $resultJson */
            $resultJson = $this->_resultJsonFactory->create();
            $resultJson->setData($result);
            return $resultJson;
        }
    }
}
