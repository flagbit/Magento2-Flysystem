<?php
namespace Flagbit\Flysystem\Controller\Adminhtml\Filesystem;

use \Flagbit\Flysystem\Block\Adminhtml\Filesystem\Tree;
use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\Controller\Result\JsonFactory;
use \Magento\Framework\View\LayoutFactory;
use \Magento\Backend\Model\Session;

/**
 * Class TreeJson
 * @package Flagbit\Flysystem\Controller\Adminhtml\Filesystem
 */
class TreeJson extends AbstractController
{
    /**
     * @var JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * @var LayoutFactory
     */
    protected $_layoutFactory;

    /**
     * TreeJson constructor.
     * @param Context $context
     * @param Manager $flysystemManager
     * @param Session $session
     * @param JsonFactory $resultJsonFactory
     * @param LayoutFactory $layoutFactory
     */
    public function __construct(
        Context $context,
        Manager $flysystemManager,
        Session $session,
        JsonFactory $resultJsonFactory,
        LayoutFactory $layoutFactory
    ) {
        $this->_layoutFactory = $layoutFactory;
        $this->_resultJsonFactory = $resultJsonFactory;
        parent::__construct($context, $flysystemManager, $session);
    }

    /**
     * Tree json action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->_resultJsonFactory->create();
        try {
            $this->_initAction();
            /** @var \Magento\Framework\View\Layout $layout */
            $layout = $this->_layoutFactory->create();
            $resultJson->setJsonData(
                $layout->createBlock(Tree::class)->getTreeJson()
            );
        } catch (\Exception $e) {
            $result = ['error' => true, 'message' => $e->getMessage()];
            $resultJson->setData($result);
        }
        return $resultJson;
    }
}
