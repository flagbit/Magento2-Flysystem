<?php
namespace Flagbit\Flysystem\Observer;

use \Flagbit\Flysystem\Model\Filesystem\TmpManager;
use \Magento\Framework\Event\Observer;
use \Magento\Framework\Event\ObserverInterface;
use \Magento\Framework\Registry;

class CategoryImage implements ObserverInterface
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var TmpManager
     */
    private $tmpManager;

    /**
     * ProductImage constructor.
     * @param Registry $registry
     * @param TmpManager $tmpManager
     */
    public function __construct(
        Registry $registry,
        TmpManager $tmpManager
    ) {
        $this->registry = $registry;
        $this->tmpManager = $tmpManager;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        try {
            $modalId = $observer->getEvent()->getData('modal_id');
            if ($modalId === 'category_modal') {
                $manager = $observer->getEvent()->getData('manager');
                $filename = $observer->getEvent()->getData('filename');
                $controller = $observer->getEvent()->getData('controller');

                $file = $this->createFileArray($manager, $filename);

                $result = $this->tmpManager->createCategoryTmp($file);
                $controller->setResult(json_encode($result));
            }
        } catch (\Exception $e) {
            return;
        }
    }

    /**
     * @param $manager
     * @param $filename
     * @return array
     */
    protected function createFileArray($manager, $filename) {
        $file = [
            'name' => basename($filename),
            'type' => $manager->getAdapter()->getMimetype($filename),
            'tmp_name' => $this->tmpManager->getAbsoluteTmpPath($filename),
            'error' => 0,
            'size' => $manager->getAdapter()->getSize($filename)
        ];

        return $file;
    }
}