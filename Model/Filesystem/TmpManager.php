<?php
namespace Flagbit\Flysystem\Model\Filesystem;

use \Flagbit\Flysystem\Adapter\FilesystemAdapter;
use \Flagbit\Flysystem\Adapter\FilesystemAdapterFactory;
use \Flagbit\Flysystem\Adapter\FilesystemManager;
use \Flagbit\Flysystem\Helper\Config;
use \Flagbit\Flysystem\Helper\Errors;
use \Flagbit\Flysystem\Helper\Filesystem;
use \Magento\Framework\UrlInterface;
use \Magento\MediaStorage\Helper\File\Storage\Database;
use \Magento\MediaStorage\Model\File\Uploader;
use \Magento\Catalog\Model\Product\Media\Config as ProductMediaConfig;
use \Magento\Framework\Exception\LocalizedException;
use \Magento\Framework\ObjectManagerInterface;
use \Magento\Store\Model\StoreManagerInterface;
use \Psr\Log\LoggerInterface;
use \Magento\Framework\Filesystem as MagentoFilesystem;
use \Magento\Framework\App\Filesystem\DirectoryList;
use \Magento\Backend\Model\Auth\Session;

/**
 * Class TmpManager
 * @package Flagbit\Flysystem\Model\Filesystem
 */
class TmpManager
{
    /**
     * @var FilesystemManager
     */
    protected $_flysystemManager;

    /**
     * @var FilesystemAdapterFactory
     */
    protected $_flysystemFactory;

    /**
     * @var Config
     */
    protected $_flysystemConfig;

    /**
     * @var Filesystem
     */
    protected $_flysystemHelper;

    /**
     * @var MagentoFilesystem
     */
    protected $_filesystem;

    /**
     * @var MagentoFilesystem\Directory\WriteInterface
     */
    protected $_directoryList;

    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * @var Session
     */
    protected $_adminSession;

    /**
     * @var ProductMediaConfig
     */
    protected $_productMediaConfig;

    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var Database
     */
    protected $_coreFileStorageDatabase;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var null|FilesystemAdapter
     */
    protected $_adapter;

    /**
     * @var string
     */
    protected $_userPreviewDir = '';

    /**
     * @var string
     */
    protected $_userTmpDir = '';

    /**
     * TmpManager constructor.
     * @param FilesystemManager $flysystemManager
     * @param FilesystemAdapterFactory $flysystemFactory
     * @param Config $flysystemConfig
     * @param Filesystem $flysystemHelper
     * @param MagentoFilesystem $filesystem
     * @param LoggerInterface $logger
     * @param Session $adminSession
     * @param ProductMediaConfig $productMediaconfig
     * @param ObjectManagerInterface $objectManager
     * @param Database $coreFileStorageDatabase
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        FilesystemManager $flysystemManager,
        FilesystemAdapterFactory $flysystemFactory,
        Config $flysystemConfig,
        Filesystem $flysystemHelper,
        MagentoFilesystem $filesystem,
        LoggerInterface $logger,
        Session $adminSession,
        ProductMediaConfig $productMediaconfig,
        ObjectManagerInterface $objectManager,
        Database $coreFileStorageDatabase,
        StoreManagerInterface $storeManager
    ) {
        $this->_flysystemManager = $flysystemManager;
        $this->_flysystemFactory = $flysystemFactory;
        $this->_flysystemConfig = $flysystemConfig;
        $this->_flysystemHelper = $flysystemHelper;
        $this->_filesystem = $filesystem;
        $this->_directoryList = $this->_filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->_logger = $logger;
        $this->_adminSession = $adminSession;
        $this->_productMediaConfig = $productMediaconfig;
        $this->_objectManager = $objectManager;
        $this->_coreFileStorageDatabase = $coreFileStorageDatabase;
        $this->_storeManager = $storeManager;

        $this->create();
    }

    /**
     * @return MagentoFilesystem\Directory\WriteInterface
     */
    public function getDirectoryListMedia()
    {
        return $this->_directoryList;
    }

    /**
     * @return FilesystemAdapter|mixed|null
     */
    public function create()
    {
        if(!$this->_adapter) {
            $path = $this->_directoryList->getAbsolutePath();
            $this->_adapter = $this->_flysystemFactory->create($this->_flysystemManager->createLocalDriver($path));
        }
        return $this->_adapter;
    }

    /**
     * @param string $file
     * @param null|string $content
     * @return bool
     */
    public function writeTmp($file, $content = null)
    {
        $this->clearTmp();
        return $this->getAdapter()->write($this->getTmpPath($file), $content);
    }

    /**
     * @param string $file
     * @return bool|false|string
     * @throws LocalizedException
     */
    public function getTmp($file)
    {
        $tmpPath = $this->getTmpPath($file);
        if($this->getAdapter()->has($tmpPath)){
            return $this->getAdapter()->read($tmpPath);
        }

        throw new LocalizedException(Errors::getErrorMessage(381, [$file]));
    }

    /**
     * @param string $file
     * @return string
     */
    public function getAbsoluteTmpPath($file)
    {
        $encodedFile = $this->_flysystemHelper->idEncode($file);
        return $this->_directoryList->getAbsolutePath().'/'.$this->getUserTmpDir().'/'.$encodedFile;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getUserTmpDir()
    {
        if(!$this->_userTmpDir) {
            $adminUser = $this->_adminSession->getUser();
            if (!$adminUser) {
                throw new LocalizedException(Errors::getErrorMessage(0));
            }
            $userDir = $this->_flysystemHelper->idEncode($adminUser->getUserName());
            $this->_userTmpDir = Config::FLYSYSTEM_DIRECTORY . '/' . Config::FLYSYSTEM_DIRECTORY_TMP . '/' . $userDir;
        }

        return $this->_userTmpDir;
    }

    /**
     * @param string $file
     * @return string
     */
    public function getTmpPath($file)
    {
        $file = $this->_flysystemHelper->idEncode($file);

        return $this->getUserTmpDir().'/'.$file;
    }

    /**
     * @return bool
     */
    public function clearTmp()
    {
        return $this->getAdapter()->deleteDir($this->getUserTmpDir());
    }

    /**
     * @param string $file
     * @param null|string $content
     * @return bool
     */
    public function writePreview($file, $content = null)
    {
        $this->clearPreview();
        $previewFilename = $this->getUserPreviewDir().'/'.basename($file);
        return $this->getAdapter()->write($previewFilename, $content);
    }

    /**
     * @return string
     * @throws LocalizedException
     */
    public function getUserPreviewDir()
    {
        if(!$this->_userPreviewDir) {
            $adminUser = $this->_adminSession->getUser();
            if (!$adminUser) {
                throw new LocalizedException(Errors::getErrorMessage(0));
            }
            $userDir = $this->_flysystemHelper->idEncode($adminUser->getUserName());
            $this->_userPreviewDir = Config::FLYSYSTEM_DIRECTORY . '/' . Config::FLYSYSTEM_DIRECTORY_PREVIEW . '/' . $userDir;
        }

        return $this->_userPreviewDir;
    }

    /**
     * @return bool
     */
    public function clearPreview()
    {
        return $this->getAdapter()->deleteDir($this->getUserPreviewDir());
    }

    /**
     * @return FilesystemAdapter|null
     */
    public function getAdapter()
    {
        return $this->_adapter;
    }

    /**
     * @param FilesystemAdapter $adapter
     */
    public function setAdapter($adapter)
    {
        $this->_adapter = $adapter;
    }

    /**
     * @param string $file
     * @param string|null $content
     * @return bool|string
     */
    public function writeWysiwygFile($file, $content = null)
    {
        $wysiwygFileConst = 'wysiwyg/'.ltrim($file, '/');
        $wysiwygFile = $wysiwygFileConst;

        for($i=1; $this->getAdapter()->has($wysiwygFile); $i++) {
            $filePathParts = explode('/', $wysiwygFileConst);
            $fileParts = explode('.', $filePathParts[(count($filePathParts)-1)]);

            $fileParts[0] = $fileParts[0].'_'.$i;
            $filePathParts[(count($filePathParts)-1)] = implode('.', $fileParts);
            $wysiwygFile = implode('/', $filePathParts);
        }

        if($this->getAdapter()->write($wysiwygFile, $content))
        {
            return $wysiwygFile;
        }

        return false;
    }

    /**
     * @param array $file
     * @return mixed
     * @throws \Exception
     */
    public function createProductTmp($file)
    {
        if(!$this->_validateUploadFile($file)) {
            throw new LocalizedException(__('File Structure is not valid'));
        }

        $tmpRoot = $this->_productMediaConfig->getBaseTmpMediaPath();

        $uploader = $this->_objectManager->create(Uploader::class, ['fileId' => $file, 'isFlysystem' => true]);
        $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
        $uploader->setAllowRenameFiles(true);
        $uploader->setFilesDispersion(true);
        $result = $uploader->save($this->_directoryList->getAbsolutePath($tmpRoot));

        unset($result['tmp_name']);
        unset($result['path']);

        $result['url'] = $this->_productMediaConfig->getTmpMediaUrl($result['file']);
        $result['file'] = $result['file'] . '.tmp';

        return $result;
    }

    /**
     * @param array $file
     * @return mixed
     * @throws LocalizedException
     * @throws \Exception
     */
    public function createCategoryTmp($file)
    {
        if(!$this->_validateUploadFile($file)) {
            throw new LocalizedException(__('File Structure is not valid'));
        }

        /** @var \Magento\Catalog\Model\ImageUploader $imageUploader*/
        $imageUploader = $this->_objectManager->get(\Magento\Catalog\CategoryImageUpload::class);
        $baseTmpPath = $imageUploader->getBaseTmpPath();

        $uploader = $this->_objectManager->create(Uploader::class, ['fileId' => $file, 'isFlysystem' => true]);
        $uploader->setAllowedExtensions($imageUploader->getAllowedExtensions());
        $uploader->setAllowRenameFiles(true);
        $uploader->setFilesDispersion(true);
        $result = $uploader->save($this->_directoryList->getAbsolutePath($baseTmpPath));

        unset($result['path']);

        if (!$result) {
            throw new LocalizedException(__('File can not be saved to the destination folder.'));
        }

        $result['tmp_name'] = str_replace('\\', '/', $result['tmp_name']);
        $result['url'] = $this->_storeManager
                ->getStore()
                ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $imageUploader->getFilePath($baseTmpPath, $result['file']);
        $result['name'] = $result['file'];

        if (isset($result['file'])) {
            try {
                $relativePath = rtrim($baseTmpPath, '/') . '/' . ltrim($result['file'], '/');
                $this->_coreFileStorageDatabase->saveFile($relativePath);
            } catch (\Exception $e) {
                $this->_logger->critical($e->getMessage());
                throw new LocalizedException(__('Something went wrong while saving the file(s).'));
            }
        }
        return $result;
    }

    /**
     * @param array $file
     * @return bool
     */
    protected function _validateUploadFile($file)
    {
        $testArray = [
            'name' => null,
            'tmp_name' => null,
            'size' => null
        ];

        return is_array($file) && !count(array_diff_key($testArray, $file));
    }
}