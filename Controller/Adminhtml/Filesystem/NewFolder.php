<?php
namespace Flagbit\Flysystem\Controller\Adminhtml\Filesystem;

use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\Controller\Result\JsonFactory;
use \Magento\Backend\Model\Session;

/**
 * Class NewFolder
 * @package Flagbit\Flysystem\Controller\Adminhtml\Filesystem
 */
class NewFolder extends AbstractController
{
    const ADMIN_RESOURCE = 'Flagbit_Flysystem::folder_create';

    /**
     * @var JsonFactory
     */
    protected $_resultJson;

    /**
     * NewFolder constructor.
     * @param Context $context
     * @param Manager $flysystemManager
     * @param Session $session
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        Manager $flysystemManager,
        Session $session,
        JsonFactory $resultJsonFactory
    ) {
        $this->_resultJson = $resultJsonFactory;
        parent::__construct($context, $flysystemManager, $session);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $manager = $this->getStorage();
            /** @var \Magento\Framework\App\Request\Http\Proxy $request */
            $request = $this->getRequest();

            /** @phan-suppress-next-line PhanUndeclaredMethod */
            $name = $request->getPost('name');
            $path = rtrim($manager->getSession()->getCurrentPath(), '/');
            $result = $manager->getAdapter()->createDir($path.'/'.$name);
        } catch(\Exception $e) {
            $result = ['error' => true, 'message' => $e->getMessage()];
        }

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->_resultJson->create();
        return $resultJson->setData($result);
    }
}