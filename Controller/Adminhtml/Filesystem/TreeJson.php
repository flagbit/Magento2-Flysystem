<?php
namespace Flagbit\Flysystem\Controller\Adminhtml\Filesystem;

use \Flagbit\Flysystem\Block\Adminhtml\Filesystem\Tree;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\Controller\Result\JsonFactory;
use \Magento\Framework\Registry;
use \Magento\Framework\View\LayoutFactory;

class TreeJson extends AbstractController
{
    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var LayoutFactory
     */
    protected $layoutFactory;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param JsonFactory $resultJsonFactory
     * @param LayoutFactory $layoutFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        JsonFactory $resultJsonFactory,
        LayoutFactory $layoutFactory
    ) {
        $this->layoutFactory = $layoutFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Tree json action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        try {
            $this->_initAction();
            /** @var \Magento\Framework\View\Layout $layout */
            $layout = $this->layoutFactory->create();
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
