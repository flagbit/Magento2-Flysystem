<?php
namespace Flagbit\Flysystem\Controller\Adminhtml\Filesystem;

use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Magento\Backend\App\Action\Context;
use \Magento\Backend\App\Action;
use \Magento\Backend\Model\Session;

/**
 * Class AbstractController
 * @package Flagbit\Flysystem\Controller\Adminhtml\Filesystem
 */
abstract class AbstractController extends Action
{
    /**
     * @var Manager
     */
    protected $_flysystemManager;

    /**
     * @var Session
     */
    protected $_session;

    /**
     * AbstractController constructor.
     * @param Context $context
     * @param Manager $flysystemManager
     * @param Session $session
     */
    public function __construct(
        Context $context,
        Manager $flysystemManager,
        Session $session
    ) {
        $this->_flysystemManager = $flysystemManager;
        $this->_session = $session;
        parent::__construct($context);
    }

    /**
     * Init storage
     *
     * @return $this
     */
    protected function _initAction()
    {
        $this->getStorage();
        return $this;
    }

    /**
     * @return \Flagbit\Flysystem\Model\Filesystem\Manager
     */
    public function getStorage()
    {
        $this->_flysystemManager->getAdapter();
        return $this->_flysystemManager;
    }
}
