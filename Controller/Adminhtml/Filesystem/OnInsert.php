<?php
namespace Flagbit\Flysystem\Controller\Adminhtml\Filesystem;

use Flagbit\Flysystem\Helper\Filesystem;
use Flagbit\Flysystem\Model\Filesystem\TmpManager;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\EntityManager\EventManager;
use Magento\Framework\Registry;
use \Magento\Backend\Model\Session;

class OnInsert extends AbstractController
{
    /**
     * @var RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var Filesystem
     */
    protected $flysystemHelper;

    /**
     * @var TmpManager
     */
    protected $tmpManager;

    /**
     * @var EventManager
     */
    protected $eventManager;

    /**
     * @var string
     */
    protected $result = '';

    /**
     * OnInsert constructor.
     * @param Context $context
     * @param Registry $coreRegistry
     * @param Session $session
     * @param RawFactory $rawFactory
     * @param Filesystem $flysystemHelper
     * @param TmpManager $tmpManager
     * @param EventManager $eventManager
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        Session $session,
        RawFactory $rawFactory,
        Filesystem $flysystemHelper,
        TmpManager $tmpManager,
        EventManager $eventManager
    ) {
        $this->resultRawFactory = $rawFactory;
        $this->flysystemHelper = $flysystemHelper;
        $this->tmpManager = $tmpManager;
        $this->eventManager = $eventManager;
        parent::__construct($context, $coreRegistry, $session);
    }

    /**
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        $manager = $this->getStorage();

        $filename = $this->getRequest()->getParam('filename');
        $filename = $this->flysystemHelper->idDecode($filename);

        $contents = $manager->getAdapter()->read($filename);

        $this->tmpManager->writeTmp($filename, $contents);

        $identifier = $this->getModalIdentifier();

        $this->eventManager->dispatch('flagbit_flysystem_oninsert_after',
            [
                'controller' => $this,
                'filename' => $filename,
                'manager' => $manager,
                'modal_id' => $identifier
            ]);

        if(empty($this->result)) {
            $this->result =  $filename;
        }

        $resultRaw = $this->resultRawFactory->create();
        return $resultRaw->setContents($this->result);
    }

    public function setResult($result)
    {
        $this->result = $result;
    }
}