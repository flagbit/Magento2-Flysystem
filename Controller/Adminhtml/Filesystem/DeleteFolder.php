<?php
namespace Flagbit\Flysystem\Controller\Adminhtml\Filesystem;

use \Magento\Backend\App\Action\Context;
use \Magento\Framework\Controller\Result\JsonFactory;
use \Magento\Framework\Registry;
use \Magento\Backend\Model\Session;

class DeleteFolder extends AbstractController
{
    /**
     * @var JsonFactory
     */
    protected $resultJson;

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
        JsonFactory $resultJsonFactory
    ) {
        $this->resultJson = $resultJsonFactory;
        parent::__construct($context, $coreRegistry, $session);
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $manager = $this->getStorage();
            $path = $manager->getSession()->getCurrentPath();
            $result = $manager->getAdapter()->deleteDir($path);
        } catch(\Exception $e) {
            $result = ['error' => true, 'message' => $e->getMessage()];
        }

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJson->create();
        return $resultJson->setData($result);
    }
}