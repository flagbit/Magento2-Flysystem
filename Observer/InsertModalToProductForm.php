<?php
namespace Flagbit\Flysystem\Observer;

use \Flagbit\Flysystem\Block\Adminhtml\Product\Modal;
use \Magento\Framework\Event\Observer;
use \Magento\Framework\Event\ObserverInterface;

/**
 * Class InsertModalToProductForm
 * @package Flagbit\Flysystem\Observer
 */
class InsertModalToProductForm implements ObserverInterface
{
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