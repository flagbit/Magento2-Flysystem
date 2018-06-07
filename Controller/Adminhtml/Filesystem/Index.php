<?php
namespace Flagbit\Flysystem\Controller\Adminhtml\Filesystem;

use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Magento\Backend\App\Action\Context;
use \Magento\Backend\Model\Session;
use \Magento\Framework\View\Result\LayoutFactory;

/**
 * Class Index
 * @package Flagbit\Flysystem\Controller\Adminhtml\Filesystem
 */
class Index extends AbstractController
{
    /**
     * @var LayoutFactory
     */
    protected $_resultLayoutFactory;

    /**
     * Index constructor.
     * @param Context $context
     * @param Manager $flysystemManager
     * @param Session $session
     * @param LayoutFactory $resultLayoutFactory
     */
    public function __construct(
        Context $context,
        Manager $flysystemManager,
        Session $session,
        LayoutFactory $resultLayoutFactory
    ) {
        $this->_resultLayoutFactory = $resultLayoutFactory;
        parent::__construct($context, $flysystemManager, $session);
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
        $resultLayout = $this->_resultLayoutFactory->create();
        $resultLayout->addHandle('overlay_popup');
        return $resultLayout;
    }
}
