<?php
namespace Flagbit\Flysystem\Controller\Adminhtml\Filesystem;

use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Flagbit\Flysystem\Model\Filesystem\UploadManager;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\Controller\Result\JsonFactory;
use \Magento\Backend\Model\Session;

/**
 * Class Upload
 * @package Flagbit\Flysystem\Controller\Adminhtml\Filesystem
 */
class Upload extends AbstractController
{
    /**
     * @var JsonFactory
     */
    protected $_resultJson;

    /**
     * @var UploadManager
     */
    protected $_uploadManager;

    /**
     * Upload constructor.
     * @param Context $context
     * @param Manager $flysystemManager
     * @param Session $session
     * @param JsonFactory $resultJsonFactory
     * @param UploadManager $uploadManager
     */
    public function __construct(
        Context $context,
        Manager $flysystemManager,
        Session $session,
        JsonFactory $resultJsonFactory,
        UploadManager $uploadManager
    ) {
        $this->_resultJson = $resultJsonFactory;
        $this->_uploadManager = $uploadManager;
        parent::__construct($context, $flysystemManager, $session);
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $manager = $this->getStorage();
            $targetPath = $manager->getSession()->getCurrentPath();
            $this->_uploadManager->upload($manager->getAdapter(), $targetPath);
            $result = ['error' => false];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->_resultJson->create();
        return $resultJson->setData($result);
    }
}