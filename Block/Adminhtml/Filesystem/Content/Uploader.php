<?php
namespace Flagbit\Flysystem\Block\Adminhtml\Filesystem\Content;

use \Magento\Backend\Block\Media\Uploader as MagentoUploader;
use \Magento\Framework\File\Size;
use \Magento\Framework\Message\ManagerInterface;
use \Magento\Framework\Registry;
use \Magento\Backend\Block\Template\Context;

class Uploader extends MagentoUploader
{

    /**
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * @var ManagerInterface
     */
    protected $_messageManager;

    /**
     * Files constructor.
     * @param Context $context
     * @param Registry $coreRegistry
     * @param ManagerInterface $messageManager
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        Size $fileSize,
        ManagerInterface $messageManager,
        array $data = []
    ) {
        $this->_coreRegistry = $coreRegistry;
        $this->_messageManager = $messageManager;
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

    protected function _getMediaType()
    {
        if ($this->hasData('media_type')) {
            return $this->_getData('media_type');
        }
        return $this->getRequest()->getParam('type');
    }
}
