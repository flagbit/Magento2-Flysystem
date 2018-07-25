<?php
namespace Flagbit\Flysystem\Observer;

use \Flagbit\Flysystem\Controller\Adminhtml\Filesystem\OnInsert;
use \Flagbit\Flysystem\Helper\Errors;
use \Flagbit\Flysystem\Helper\Filesystem;
use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Flagbit\Flysystem\Model\Filesystem\TmpManager;
use \Magento\Framework\Event\Observer;
use \Magento\Framework\Event\ObserverInterface;
use \Magento\Framework\Exception\LocalizedException;
use \Psr\Log\LoggerInterface;

/**
 * Class CategoryImage
 * @package Flagbit\Flysystem\Observer
 */
class CmsImage implements ObserverInterface
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
     * @var Filesystem
     */
    private $_flysystemHelper;

    /**
     * CmsImage constructor.
     * @param TmpManager $tmpManager
     * @param LoggerInterface $logger
     * @param Filesystem $flysystemHelper
     */
    public function __construct(
        TmpManager $tmpManager,
        LoggerInterface $logger,
        Filesystem $flysystemHelper
    ) {
        $this->_tmpManager = $tmpManager;
        $this->_logger = $logger;
        $this->_flysystemHelper = $flysystemHelper;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        try {
            $modalId = $observer->getEvent()->getData('modal_id');
            if ($modalId === 'flagbit_cms_modal') {
                $manager = $observer->getEvent()->getData('manager');
                $filename = $observer->getEvent()->getData('filename');
                $controller = $observer->getEvent()->getData('controller');
                $as_is = $observer->getEvent()->getData('as_is');

                if(!is_a($manager, Manager::class) || !is_a($controller, OnInsert::class)) {
                    throw new \Exception(Errors::getErrorMessage(621, [get_class($this), $observer->getEventName()]));
                }

                $content = $manager->getAdapter()->read($filename);

                $fullFilePath = trim($manager->getPath(), '/').'/'.trim($filename, '/');
                $mediaPath = trim($this->_tmpManager->getDirectoryListMedia()->getAbsolutePath(), '/');

                if(strpos($fullFilePath, $mediaPath) === false) {
                    $filename = $this->_tmpManager->writeWysiwygFile(basename($filename), $content);
                    if($filename === false) {
                        throw new LocalizedException(__('File could not be written to wysiwyg folder!'));
                    }
                } else {
                    $filename = trim(str_replace($mediaPath, '', $fullFilePath), '/');
                }

                $image = $this->_flysystemHelper->getImageHtmlDeclaration($filename, $as_is);
                $controller->setResult($image);
            }
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
            return;
        }
    }
}