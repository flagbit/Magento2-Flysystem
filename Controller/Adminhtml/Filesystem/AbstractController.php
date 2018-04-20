<?php
namespace Flagbit\Flysystem\Controller\Adminhtml\Filesystem;

use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\Registry;
use \Magento\Backend\App\Action;

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
     * @param Context $context
     * @param Registry $coreRegistry
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry
    ) {
        $this->_coreRegistry = $coreRegistry;
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
}
