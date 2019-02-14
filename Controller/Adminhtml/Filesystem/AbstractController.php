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
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _initAction(): self
    {
        $this->getStorage();
        return $this;
    }

    /**
     * @return Manager
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getStorage(): Manager
    {
        $this->_flysystemManager->getAdapter();
        return $this->_flysystemManager;
    }
}
