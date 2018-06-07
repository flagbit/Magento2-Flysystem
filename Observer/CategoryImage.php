<?php
namespace Flagbit\Flysystem\Observer;

use \Flagbit\Flysystem\Model\Filesystem\TmpManager;
use \Magento\Framework\Event\Observer;
use \Magento\Framework\Event\ObserverInterface;

/**
 * Class CategoryImage
 * @package Flagbit\Flysystem\Observer
 */
class CategoryImage implements ObserverInterface
{
    /**
     * @var TmpManager
     */
    private $_tmpManager;

    /**
     * ProductImage constructor.
     * @param TmpManager $tmpManager
     */
    public function __construct(
        TmpManager $tmpManager
    ) {
        $this->_tmpManager = $tmpManager;
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

                $result = $this->_tmpManager->createCategoryTmp($file);
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
            'tmp_name' => $this->_tmpManager->getAbsoluteTmpPath($filename),
            'error' => 0,
            'size' => $manager->getAdapter()->getSize($filename)
        ];

        return $file;
    }
}