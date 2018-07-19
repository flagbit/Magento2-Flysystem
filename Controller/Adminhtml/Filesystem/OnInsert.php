<?php
namespace Flagbit\Flysystem\Controller\Adminhtml\Filesystem;

use \Flagbit\Flysystem\Helper\Filesystem;
use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Flagbit\Flysystem\Model\Filesystem\TmpManager;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\Controller\Result\RawFactory;
use \Magento\Backend\Model\Session;
use \Psr\Log\LoggerInterface;

/**
 * Class OnInsert
 * @package Flagbit\Flysystem\Controller\Adminhtml\Filesystem
 */
class OnInsert extends AbstractController
{
    const ADMIN_RESOURCE = 'Flagbit_Flysystem::file_insert';

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
     * @var LoggerInterface
     */
    protected $_logger;

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
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        Manager $flysystemManager,
        Session $session,
        RawFactory $rawFactory,
        Filesystem $flysystemHelper,
        TmpManager $tmpManager,
        LoggerInterface $logger
    ) {
        $this->_resultRawFactory = $rawFactory;
        $this->_flysystemHelper = $flysystemHelper;
        $this->_tmpManager = $tmpManager;
        $this->_logger = $logger;
        parent::__construct($context, $flysystemManager, $session);
    }

    /**
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        try {
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

            if (empty($this->_result)) {
                $this->setResult($filename);
            }
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }

        $resultRaw = $this->_resultRawFactory->create();
        return $resultRaw->setContents($this->_result);
    }

    /**
     * @param string $result
     */
    public function setResult($result)
    {
        $this->_result = $result;
    }
}