<?php
namespace Flagbit\Flysystem\Controller\Adminhtml\Filesystem;

use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\Registry;
use \Magento\Backend\App\Action;
use \Magento\Backend\Model\Session;

/**
 * Filesystem manage controller for Flysystem Mediabrowser
 */
abstract class AbstractController extends Action
{
    public const ADMIN_RESOURCE = 'Flagbit_Flysystem::filesystem';

    /**
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * @var Manager
     */
    protected $_manager;

    /**
     * @var Session
     */
    protected $_session;

    /**
     * AbstractController constructor.
     * @param Context $context
     * @param Registry $coreRegistry
     * @param Session $session
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        Session $session
    ) {
        $this->_coreRegistry = $coreRegistry;
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
        if(!$this->_coreRegistry->registry('flysystem_manager')) {
            $manager = $this->_objectManager->create(Manager::class);
            $manager->create();
            $this->_coreRegistry->register('flysystem_manager', $manager);
        }

        return $this->_coreRegistry->registry('flysystem_manager');
    }

    public function setModalIdentifier($identifier)
    {
        return $this->_session->setFlysystemModalId($identifier);
    }

    public function getModalIdentifier()
    {
        return $this->_session->getFlysystemModalId();
    }
}
