<?php
namespace Flagbit\Flysystem\Controller\Adminhtml\Filesystem;

use \Magento\Backend\App\Action\Context;
use \Magento\Backend\Model\Session;
use \Magento\Framework\Registry;
use \Magento\Framework\View\Result\LayoutFactory;

class Index extends AbstractController
{
    /**
     * @var LayoutFactory
     */
    protected $resultLayoutFactory;

    /**
     * Index constructor.
     * @param Context $context
     * @param Registry $coreRegistry
     * @param Session $session
     * @param LayoutFactory $resultLayoutFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        Session $session,
        LayoutFactory $resultLayoutFactory
    ) {
        $this->resultLayoutFactory = $resultLayoutFactory;
        $this->session = $session;
        parent::__construct($context, $coreRegistry, $session);
    }

    /**
     * Index action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $this->_initAction();

        $identifier = $this->getRequest()->getParam('identifier');
        $this->getStorage()->setModalIdentifier($identifier);

        /** @var \Magento\Framework\View\Result\Layout $resultLayout */
        $resultLayout = $this->resultLayoutFactory->create();
        $resultLayout->addHandle('overlay_popup');
        return $resultLayout;
    }
}
