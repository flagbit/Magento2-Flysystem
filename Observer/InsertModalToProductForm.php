<?php
namespace Flagbit\Flysystem\Observer;

use \Flagbit\Flysystem\Block\Adminhtml\Product\Modal;
use \Flagbit\Flysystem\Model\Filesystem\TmpManager;
use \Magento\Framework\Event\Observer;
use \Magento\Framework\Event\ObserverInterface;
use \Magento\Framework\Registry;

class InsertModalToProductForm implements ObserverInterface
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * InsertModalToProductForm constructor.
     * @param Registry $registry
     * @param TmpManager $tmpManager
     */
    public function __construct(
        Registry $registry,
        TmpManager $tmpManager
    ) {
        $this->registry = $registry;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        try {
            $observer->getBlock()->setTemplate('Flagbit_Flysystem::/product/form/gallery.phtml');
            $observer->getBlock()->addChild('flysystem-modal', Modal::class);
        } catch (\Exception $e) {
            return;
        }
    }
}