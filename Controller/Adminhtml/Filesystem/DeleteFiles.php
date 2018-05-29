<?php
namespace Flagbit\Flysystem\Controller\Adminhtml\Filesystem;

use \Flagbit\Flysystem\Helper\Filesystem;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\Controller\Result\JsonFactory;
use \Magento\Framework\Registry;
use \Magento\Backend\Model\Session;

class DeleteFiles extends AbstractController
{
    /**
     * @var JsonFactory
     */
    protected $resultJson;

    /**
     * @var Filesystem
     */
    protected $flysystemHelper;

    /**
     * DeleteFiles constructor.
     * @param Context $context
     * @param Registry $coreRegistry
     * @param Session $session
     * @param JsonFactory $resultJsonFactory
     * @param Filesystem $flysystemHelper
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        Session $session,
        JsonFactory $resultJsonFactory,
        Filesystem $flysystemHelper
    ) {
        $this->resultJson = $resultJsonFactory;
        $this->flysystemHelper = $flysystemHelper;
        parent::__construct($context, $coreRegistry, $session);
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            if (!$this->getRequest()->isPost()) {
                throw new \Exception('Wrong request.');
            }
            $files = $this->getRequest()->getParam('files');
            $manager = $this->getStorage();

            foreach($files as $file) {
                $file = $this->flysystemHelper->idDecode($file);
                $manager->getAdapter()->delete($file);
            }
            $result = ['error' => false];
        } catch(\Exception $e) {
            $result = ['error' => true, 'message' => $e->getMessage()];
        }

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJson->create();
        return $resultJson->setData($result);
    }
}