<?php
namespace Flagbit\Flysystem\Block\Adminhtml\Filesystem\Content;

use \Magento\Backend\Block\Media\Uploader as MagentoUploader;
use \Magento\Framework\File\Size;
use \Magento\Backend\Block\Template\Context;

/**
 * Class Uploader
 * @package Flagbit\Flysystem\Block\Adminhtml\Filesystem\Content
 */
class Uploader extends MagentoUploader
{
    /**
     * Uploader constructor.
     * @param Context $context
     * @param Size $fileSize
     * @param array $data
     */
    public function __construct(
        Context $context,
        Size $fileSize,
        array $data = []
    ) {
        parent::__construct($context, $fileSize, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $type = $this->_getMediaType();

        $this->getConfig()->setUrl(
            $this->_urlBuilder->addSessionParam()->getUrl('flagbit_flysystem/*/upload', ['type' => $type])
        )->setFileField(
            \Flagbit\Flysystem\Helper\Config::FLYSYSTEM_UPLOAD_ID
        );
    }

    /**
     * @return string|null
     */
    protected function _getMediaType()
    {
        if ($this->hasData('media_type')) {
            return $this->_getData('media_type');
        }
        return $this->getRequest()->getParam('type');
    }
}
