<?php
namespace Flagbit\Flysystem\Block\Adminhtml\Filesystem\Content;

use \Flagbit\Flysystem\Helper\Config;
use \Flagbit\Flysystem\Helper\Filesystem;
use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Magento\Framework\Filesystem\DirectoryList;
use \Magento\Framework\Message\ManagerInterface;
use \Magento\Backend\Block\Template;
use \Magento\Backend\Block\Template\Context;
use \Magento\Framework\UrlInterface;
use \Magento\Framework\View\Element\Messages;
use \Magento\Store\Model\StoreManagerInterface;

/**
 * Class Files
 * @package Flagbit\Flysystem\Block\Adminhtml\Filesystem\Content
 */
class Files extends Template
{
    /**
     * Files collection object
     *
     * @var array
     */
    protected $_filesCollection = [];

    /**
     * @var Manager
     */
    protected $_flysystemManager;

    /**
     * @var Filesystem
     */
    protected $_flysystemHelper;

    /**
     * @var Config
     */
    protected $_flysystemConfig;

    /**
     * @var DirectoryList
     */
    protected $_directoryList;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var ManagerInterface
     */
    protected $_messageManager;

    /**
     * @var Messages
     */
    protected $_messages;

    /**
     * Files constructor.
     * @param Context $context
     * @param Manager $flysystemManager
     * @param Filesystem $flysystemHelper
     * @param Config $flysystemConfig
     * @param DirectoryList $directoryList
     * @param StoreManagerInterface $storeManager
     * @param ManagerInterface $messageManager
     * @param Messages $messages
     * @param array $data
     */
    public function __construct(
        Context $context,
        Manager $flysystemManager,
        Filesystem $flysystemHelper,
        Config $flysystemConfig,
        DirectoryList $directoryList,
        StoreManagerInterface $storeManager,
        ManagerInterface $messageManager,
        Messages $messages,
        array $data = []
    ) {
        $this->_flysystemManager = $flysystemManager;
        $this->_flysystemHelper = $flysystemHelper;
        $this->_flysystemConfig = $flysystemConfig;
        $this->_directoryList = $directoryList;
        $this->_storeManager = $storeManager;
        $this->_messageManager = $messageManager;
        $this->_messages = $messages;
        parent::__construct($context, $data);
    }

    /**
     * Prepared Files collection for current directory
     *
     * @return array
     */
    public function getFiles()
    {
        try {
            if (count($this->_filesCollection) === 0) {
                $path = $this->_flysystemHelper->getCurrentPath();

                $contents = $this->_flysystemManager->getAdapter()->listContents($path);
                foreach ($contents as $file) {
                    if ($this->validateFile($file)) {
                        $this->_filesCollection[] = $file;
                    }
                }
            }
        } catch (\Exception $e) {
            $this->_messageManager->addErrorMessage($e->getMessage());
            return [];
        }

        return $this->_filesCollection;
    }

    public function validateFile($file)
    {
        if($file['type'] === 'file' && $file['basename'][0] !== '.') {
            $fileParts = explode('.', $file['basename']);
            $supportedFileTypes = $this->_flysystemConfig->getSupportedFileTypes();
            if(in_array($fileParts[count($fileParts)-1], $supportedFileTypes)) {
                return true;
            }
        }

        return false;
    }

    public function getMessages()
    {
        $this->_messages->setMessages($this->_messageManager->getMessages());
        return $this->_messages->getGroupedHtml();
    }

    /**
     * Files collection count getter
     *
     * @return int
     */
    public function getFilesCount()
    {
        $asdf = count($this->getFiles());

        return $asdf;
    }

    /**
     * @param $file
     * @return string
     */
    public function getFileId($file)
    {
        return $this->_flysystemHelper->idEncode($file['path']);
    }

    /**
     * @param $file
     * @return string
     */
    public function getFileShortName($file) {
        return $this->_flysystemHelper->getShortFilename($file['path']);
    }

    /**
     * @param $file
     * @return string
     */
    public function getFileEnding($file) {
        if(strstr($file['basename'], '.') === false) {
            return 'unknown';
        }

        $fileParts = explode('.', $file['basename']);

        return '.'.$fileParts[(count($fileParts)-1)];
    }

    /**
     * @param $file
     * @return bool|mixed
     */
    public function getFileThumbUrl($file) {
        if($this->validateFile($file)) {
            $mediaPath = $this->_directoryList->getPath('media');
            $filesystemPath = trim($this->_flysystemManager->getPath(), '/');
            $fullPath = '/'.$filesystemPath.'/'.$file['path'];

            if(strstr($fullPath, $mediaPath) === false) {
                return false;
            }

            return $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA ).str_replace($mediaPath, '', $fullPath);
        }

        return false;
    }
}
