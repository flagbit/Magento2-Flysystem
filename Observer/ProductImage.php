<?php
namespace Flagbit\Flysystem\Observer;

use \Flagbit\Flysystem\Controller\Adminhtml\Filesystem\OnInsert;
use \Flagbit\Flysystem\Helper\Errors;
use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Flagbit\Flysystem\Model\Filesystem\TmpManager;
use \Magento\Framework\Event\Observer;
use \Magento\Framework\Event\ObserverInterface;
use \Psr\Log\LoggerInterface;

/**
 * Class ProductImage
 * @package Flagbit\Flysystem\Observer
 */
class ProductImage implements ObserverInterface
{
    /**
     * @var TmpManager
     */
    private $_tmpManager;

    /**
     * @var LoggerInterface
     */
    private $_logger;

    /**
     * ProductImage constructor.
     * @param TmpManager $tmpManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        TmpManager $tmpManager,
        LoggerInterface $logger
    ) {
        $this->_tmpManager = $tmpManager;
        $this->_logger = $logger;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        try {
            $modalId = $observer->getEvent()->getData('modal_id');
            if ($modalId === 'product_gallery') {
                $manager = $observer->getEvent()->getData('manager');
                $filename = $observer->getEvent()->getData('filename');
                $controller = $observer->getEvent()->getData('controller');

                if(!is_a($manager, Manager::class) || !is_a($controller, OnInsert::class)) {
                    throw new \Exception(Errors::getErrorMessage(621, [get_class($this), $observer->getEventName()]));
                }

                $file = $this->createFileArray($manager, $filename);

                $result = $this->_tmpManager->createProductTmp($file);
                $controller->setResult(json_encode($result));
            }
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
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