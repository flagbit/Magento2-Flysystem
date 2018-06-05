<?php
namespace Flagbit\Flysystem\Model\Filesystem;

use \Flagbit\Flysystem\Adapter\FilesystemAdapter;
use \Flagbit\Flysystem\Adapter\FilesystemAdapterFactory;
use \Flagbit\Flysystem\Adapter\FilesystemManager;
use \Flagbit\Flysystem\Helper\Config;
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

class TmpManager
{
    /**
     * @var FilesystemManager
     */
    protected $flysystemManager;

    /**
     * @var FilesystemAdapterFactory
     */
    protected $flysystemFactory;

    /**
     * @var Config
     */
    protected $flysystemConfig;

    /**
     * @var Filesystem
     */
    protected $flysystemHelper;

    /**
     * @var MagentoFilesystem
     */
    protected $filesystem;

    /**
     * @var MagentoFilesystem\Directory\WriteInterface
     */
    protected $directoryList;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Session
     */
    protected $adminSession;

    /**
     * @var ProductMediaConfig
     */
    protected $productMediaConfig;

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var Database
     */
    protected $coreFileStorageDatabase;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var null|FilesystemAdapter
     */
    protected $adapter;

    /**
     * TmpManager constructor.
     * @param FilesystemManager $filesystemManager
     * @param FilesystemAdapterFactory $filesystemAdapterFactory
     * @param Config $config
     * @param Filesystem $flysystemHelper
     * @param MagentoFilesystem $filesystem
     * @param DirectoryList $directoryList
     * @param LoggerInterface $logger
     * @param Session $adminSession
     * @param ProductMediaConfig $productMediaconfig
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(
        FilesystemManager $filesystemManager,
        FilesystemAdapterFactory $filesystemAdapterFactory,
        Config $config,
        Filesystem $flysystemHelper,
        MagentoFilesystem $filesystem,
        DirectoryList $directoryList,
        LoggerInterface $logger,
        Session $adminSession,
        ProductMediaConfig $productMediaconfig,
        ObjectManagerInterface $objectManager,
        Database $coreFileStorageDatabase,
        StoreManagerInterface $storeManager
    ) {
        $this->flysystemManager = $filesystemManager;
        $this->flysystemFactory = $filesystemAdapterFactory;
        $this->flysystemConfig = $config;
        $this->flysystemHelper = $flysystemHelper;
        $this->filesystem = $filesystem;
        $this->directoryList = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->logger = $logger;
        $this->adminSession = $adminSession;
        $this->productMediaConfig = $productMediaconfig;
        $this->objectManager = $objectManager;
        $this->coreFileStorageDatabase = $coreFileStorageDatabase;
        $this->storeManager = $storeManager;

        $this->create();
    }

    /**
     * @return FilesystemAdapter|mixed|null
     */
    public function create()
    {
        if(!$this->adapter) {
            $path = $this->directoryList->getAbsolutePath();
            $this->adapter = $this->flysystemFactory->create($this->flysystemManager->createLocalDriver($path));
        }
        return $this->adapter;
    }

    /**
     * @param $file
     * @param null $content
     * @return bool
     */
    public function writeTmp($file, $content = null)
    {
        $this->clearTmp();
        return $this->getAdapter()->write($this->getTmpPath($file), $content);
    }

    /**
     * @param $file
     * @return bool|false|string
     * @throws LocalizedException
     */
    public function getTmp($file)
    {
        if($this->getAdapter()->has($this->getTmpPath($file))){
            return $this->getAdapter()->read($this->getTmpPath($file));
        }

        throw new LocalizedException(__('Could not find '.$file.' in Tmp Path'));
    }

    /**
     * @param $file
     * @return string
     */
    public function getAbsoluteTmpPath($file)
    {
        $encodedFile = $this->flysystemHelper->idEncode($file);
        return $this->directoryList->getAbsolutePath().'/'.$this->getUserTmpDir().'/'.$encodedFile;
    }

    /**
     * @return string
     */
    protected function getUserTmpDir()
    {
        $userDir = $this->flysystemHelper->idEncode($this->adminSession->getUser()->getUserName());
        return Config::FLYSYSTEM_DIRECTORY.'/'.Config::FLYSYSTEM_DIRECTORY_TMP.'/'.$userDir;
    }

    /**
     * @param $file
     * @return string
     */
    protected function getTmpPath($file)
    {
        $file = $this->flysystemHelper->idEncode($file);

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
     * @return FilesystemAdapter|null
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param $adapter
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param $file
     * @return mixed
     */
    public function createProductTmp($file)
    {
        $tmpRoot = $this->productMediaConfig->getBaseTmpMediaPath();

        $uploader = $this->objectManager->create(Uploader::class, ['fileId' => $file, 'isFlysystem' => true]);
        $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
        $uploader->setAllowRenameFiles(true);
        $uploader->setFilesDispersion(true);
        $result = $uploader->save($this->directoryList->getAbsolutePath($tmpRoot));

        unset($result['tmp_name']);
        unset($result['path']);

        $result['url'] = $this->productMediaConfig->getTmpMediaUrl($result['file']);
        $result['file'] = $result['file'] . '.tmp';

        return $result;
    }

    public function createCategoryTmp($file)
    {
        /** @var \Magento\Catalog\Model\ImageUploader $imageUploader*/
        $imageUploader = $this->objectManager->get(\Magento\Catalog\CategoryImageUpload::class);
        $baseTmpPath = $imageUploader->getBaseTmpPath();

        $uploader = $this->objectManager->create(Uploader::class, ['fileId' => $file, 'isFlysystem' => true]);
        $uploader->setAllowedExtensions($imageUploader->getAllowedExtensions());
        $uploader->setAllowRenameFiles(true);
        $uploader->setFilesDispersion(true);
        $result = $uploader->save($this->directoryList->getAbsolutePath($baseTmpPath));

        unset($result['path']);

        if (!$result) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('File can not be saved to the destination folder.')
            );
        }

        $result['tmp_name'] = str_replace('\\', '/', $result['tmp_name']);
        $result['url'] = $this->storeManager
                ->getStore()
                ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $imageUploader->getFilePath($baseTmpPath, $result['file']);
        $result['name'] = $result['file'];

        if (isset($result['file'])) {
            try {
                $relativePath = rtrim($baseTmpPath, '/') . '/' . ltrim($result['file'], '/');
                $this->coreFileStorageDatabase->saveFile($relativePath);
            } catch (\Exception $e) {
                $this->logger->critical($e);
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Something went wrong while saving the file(s).')
                );
            }
        }
        return $result;
    }

    /**
     * Create Array out of File like $_FILE[]
     */
    protected function _createFileArray()
    {
        $file = [

        ];

        return $file;
    }
}