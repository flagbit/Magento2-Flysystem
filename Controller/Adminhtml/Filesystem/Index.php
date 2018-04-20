<?php
namespace Flagbit\Flysystem\Controller\Adminhtml\Filesystem;

use \Magento\Backend\App\Action\Context;
use \Magento\Framework\Registry;
use \Magento\Framework\View\Result\LayoutFactory;

class Index extends AbstractController
{
    /**
     * @var LayoutFactory
     */
    protected $resultLayoutFactory;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param LayoutFactory $resultLayoutFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        LayoutFactory $resultLayoutFactory
    ) {
        $this->resultLayoutFactory = $resultLayoutFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Index action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $storeId = (int)$this->getRequest()->getParam('store');
        $this->_initAction();
        /** @var \Magento\Framework\View\Result\Layout $resultLayout */
        $resultLayout = $this->resultLayoutFactory->create();
        $resultLayout->addHandle('overlay_popup');
        return $resultLayout;
    }
}
