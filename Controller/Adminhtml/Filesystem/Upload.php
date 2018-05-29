<?php
namespace Flagbit\Flysystem\Controller\Adminhtml\Filesystem;

use Flagbit\Flysystem\Model\Filesystem\UploadManager;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\Controller\Result\JsonFactory;
use \Magento\Framework\Registry;
use \Magento\Backend\Model\Session;

class Upload extends AbstractController
{
    /**
     * @var JsonFactory
     */
    protected $resultJson;

    /**
     * @var UploadManager
     */
    protected $uploadManager;

    /**
     * NewFolder constructor.
     * @param Context $context
     * @param Registry $coreRegistry
     * @param Session $session
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        Session $session,
        JsonFactory $resultJsonFactory,
        UploadManager $uploadManager
    ) {
        $this->resultJson = $resultJsonFactory;
        $this->uploadManager = $uploadManager;
        parent::__construct($context, $coreRegistry, $session);
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $manager = $this->getStorage();
            $targetPath = $manager->getSession()->getCurrentPath();
            $this->uploadManager->upload($manager->getAdapter(), $targetPath);
            $result = ['error' => false];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJson->create();
        return $resultJson->setData($result);
    }
}