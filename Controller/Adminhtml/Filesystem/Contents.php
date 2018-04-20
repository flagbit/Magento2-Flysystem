<?php
namespace Flagbit\Flysystem\Controller\Adminhtml\Filesystem;

use \Flagbit\Flysystem\Helper\Filesystem;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\Controller\Result\JsonFactory;
use \Magento\Framework\Registry;
use \Magento\Framework\View\Result\LayoutFactory;

class Contents extends AbstractController
{
    /**
     * @var LayoutFactory
     */
    protected $resultLayoutFactory;

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param LayoutFactory $resultLayoutFactory
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        LayoutFactory $resultLayoutFactory,
        JsonFactory $resultJsonFactory
    ) {
        $this->resultLayoutFactory = $resultLayoutFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Save current path in session
     *
     * @return $this
     */
    protected function _saveSessionCurrentPath()
    {
        $this->getStorage()->getSession()->setCurrentPath(
            $this->_objectManager->get(Filesystem::class)->getCurrentPath()
        );
        return $this;
    }

    /**
     * Contents action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $this->_initAction()->_saveSessionCurrentPath();
            /** @var \Magento\Framework\View\Result\Layout $resultLayout */
            $resultLayout = $this->resultLayoutFactory->create();
            return $resultLayout;
        } catch (\Exception $e) {
            $result = ['error' => true, 'message' => $e->getMessage()];
            /** @var \Magento\Framework\Controller\Result\Json $resultJson */
            $resultJson = $this->resultJsonFactory->create();
            $resultJson->setData($result);
            return $resultJson;
        }
    }
}
