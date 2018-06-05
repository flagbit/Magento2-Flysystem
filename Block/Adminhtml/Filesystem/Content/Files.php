<?php
namespace Flagbit\Flysystem\Block\Adminhtml\Filesystem\Content;

use \Flagbit\Flysystem\Helper\Config;
use \Flagbit\Flysystem\Helper\Filesystem;
use \Magento\Framework\Filesystem\DirectoryList;
use \Magento\Framework\Message\ManagerInterface;
use \Magento\Framework\Registry;
use \Magento\Backend\Block\Template;
use \Magento\Backend\Block\Template\Context;
use \Magento\Framework\UrlInterface;
use \Magento\Framework\View\Element\Messages;
use \Magento\Store\Model\StoreManagerInterface;

class Files extends Template
{
    /**
     * Files collection object
     *
     * @var array
     */
    protected $_filesCollection = [];

    /**
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * @var Filesystem
     */
    protected $_filesystemHelper;

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
     * @param Registry $coreRegistry
     * @param Filesystem $filesystemHelper
     * @param Config $flysystemConfig
     * @param DirectoryList $directoryList
     * @param StoreManagerInterface $storeManager
     * @param ManagerInterface $messageManager
     * @param Messages $messages
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        Filesystem $filesystemHelper,
        Config $flysystemConfig,
        DirectoryList $directoryList,
        StoreManagerInterface $storeManager,
        ManagerInterface $messageManager,
        Messages $messages,
        array $data = []
    ) {
        $this->_coreRegistry = $coreRegistry;
        $this->_filesystemHelper = $filesystemHelper;
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
                $manager = $this->_coreRegistry->registry('flysystem_manager');
                $path = $this->_filesystemHelper->getCurrentPath();

                $contents = $manager->getAdapter()->listContents($path);
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
        return $this->_filesystemHelper->idEncode($file['path']);
    }

    /**
     * @param $file
     * @return string
     */
    public function getFileShortName($file) {
        return $this->_filesystemHelper->getShortFilename($file['path']);
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
            $filesystemPath = trim($this->_coreRegistry->registry('flysystem_manager')->getPath(), '/');
            $fullPath = '/'.$filesystemPath.'/'.$file['path'];

            if(strstr($fullPath, $mediaPath) === false) {
                return false;
            }

            return $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA ).str_replace($mediaPath, '', $fullPath);
        }

        return false;
    }
}
