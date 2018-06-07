<?php
namespace Flagbit\Flysystem\Controller\Adminhtml\Filesystem;

use \Flagbit\Flysystem\Helper\Filesystem;
use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Flagbit\Flysystem\Model\Filesystem\TmpManager;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\Controller\Result\RawFactory;
use \Magento\Framework\EntityManager\EventManager;
use \Magento\Backend\Model\Session;

/**
 * Class OnInsert
 * @package Flagbit\Flysystem\Controller\Adminhtml\Filesystem
 */
class OnInsert extends AbstractController
{
    /**
     * @var RawFactory
     */
    protected $_resultRawFactory;

    /**
     * @var Filesystem
     */
    protected $_flysystemHelper;

    /**
     * @var TmpManager
     */
    protected $_tmpManager;

    /**
     * @var EventManager
     */
    protected $_eventManager;

    /**
     * @var string
     */
    protected $_result = '';

    /**
     * OnInsert constructor.
     * @param Context $context
     * @param Manager $flysystemManager
     * @param Session $session
     * @param RawFactory $rawFactory
     * @param Filesystem $flysystemHelper
     * @param TmpManager $tmpManager
     * @param EventManager $eventManager
     */
    public function __construct(
        Context $context,
        Manager $flysystemManager,
        Session $session,
        RawFactory $rawFactory,
        Filesystem $flysystemHelper,
        TmpManager $tmpManager,
        EventManager $eventManager
    ) {
        $this->_resultRawFactory = $rawFactory;
        $this->_flysystemHelper = $flysystemHelper;
        $this->_tmpManager = $tmpManager;
        $this->_eventManager = $eventManager;
        parent::__construct($context, $flysystemManager, $session);
    }

    /**
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        $manager = $this->getStorage();

        $filename = $this->getRequest()->getParam('filename');
        $filename = $this->_flysystemHelper->idDecode($filename);

        $contents = $manager->getAdapter()->read($filename);

        $this->_tmpManager->writeTmp($filename, $contents);

        $identifier = $manager->getModalIdentifier();

        $this->_eventManager->dispatch('flagbit_flysystem_oninsert_after',
            [
                'controller' => $this,
                'filename' => $filename,
                'manager' => $manager,
                'modal_id' => $identifier
            ]);

        if(empty($this->result)) {
            $this->result =  $filename;
        }

        $resultRaw = $this->_resultRawFactory->create();
        return $resultRaw->setContents($this->result);
    }

    public function setResult($result)
    {
        $this->result = $result;
    }
}