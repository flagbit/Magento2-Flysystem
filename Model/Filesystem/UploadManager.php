<?php
namespace Flagbit\Flysystem\Model\Filesystem;

use \Flagbit\Flysystem\Adapter\FilesystemAdapter;
use \Flagbit\Flysystem\Adapter\FilesystemAdapterFactory;
use \Flagbit\Flysystem\Adapter\FilesystemManager;
use \Flagbit\Flysystem\Helper\Config;
use \Flagbit\Flysystem\Helper\Errors;
use \Magento\MediaStorage\Model\File\Uploader;
use \Magento\MediaStorage\Model\File\UploaderFactory;
use \Psr\Log\LoggerInterface;

/**
 * Class UploadManager
 * @package Flagbit\Flysystem\Model\Filesystem
 */
class UploadManager
{
    const SERVER_TMP_PATH = '/tmp';

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
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * @var UploaderFactory
     */
    protected $_uploaderFactory;

    /**
     * @var null|Uploader
     */
    protected $_uploader;

    /**
     * @var null|FilesystemAdapter
     */
    protected $_adapter;

    /**
     * UploadManager constructor.
     * @param FilesystemManager $flysystemManager
     * @param FilesystemAdapterFactory $flysystemFactory
     * @param Config $flysystemConfig
     * @param LoggerInterface $logger
     * @param UploaderFactory $uploaderFactory
     */
    public function __construct(
        FilesystemManager $flysystemManager,
        FilesystemAdapterFactory $flysystemFactory,
        Config $flysystemConfig,
        LoggerInterface $logger,
        UploaderFactory $uploaderFactory
    ) {
        $this->_flysystemManager = $flysystemManager;
        $this->_flysystemFactory = $flysystemFactory;
        $this->_flysystemConfig = $flysystemConfig;
        $this->_logger = $logger;
        $this->_uploaderFactory = $uploaderFactory;

        $this->create();
        $this->setUploadFile();
    }

    /**
     * @return FilesystemAdapter|null
     */
    public function create(): ?FilesystemAdapter
    {
        if(!$this->_adapter) {
            $this->_adapter = $this->_flysystemFactory->create($this->_flysystemManager->createLocalDriver(self::SERVER_TMP_PATH));
        }
        return $this->_adapter;
    }

    /**
     * @return Uploader|null
     */
    public function getUploader(): ?Uploader
    {
        return $this->_uploader;
    }

    /**
     * @return FilesystemAdapter|null
     */
    public function getAdapter(): ?FilesystemAdapter
    {
        return $this->_adapter;
    }

    /**
     * @param string $fileId
     * @return bool
     */
    public function setUploadFile(string $fileId = \Flagbit\Flysystem\Helper\Config::FLYSYSTEM_UPLOAD_ID): bool
    {
        try {
            $this->_uploader = $this->_uploaderFactory->create(['fileId' => $fileId]);
            return true;
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
            return false;
        }
    }

    /**
     * @param array $file
     * @return void
     * @throws \Exception
     */
    public function validateFileType(array $file)
    {
        $filetype = '';

        if(isset($file['name'])) {
            $parts = explode('.', $file['name']);
            $supportedFileTypes = $this->_flysystemConfig->getSupportedFileTypes();

            $filetype = $parts[count($parts)-1];
            if(in_array($filetype, $supportedFileTypes)) {
                return;
            }
        }

        throw new \Exception(Errors::getErrorMessage(382, [$filetype]));
    }

    /**
     * @param FilesystemAdapter $adapter
     * @param string $targetPath
     * @return bool
     * @throws \Exception
     * @throws \League\Flysystem\FileExistsException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function upload(FilesystemAdapter $adapter, string $targetPath): bool
    {
        $file = $this->getUploader()->validateFile();

        $this->validateFileType($file);

        if(!isset($file['tmp_name'])) {
            throw new \Exception(Errors::getErrorMessage(501));
        }

        $contents = $this->getAdapter()->read(basename($file['tmp_name']));
        $filename = $file['name'];

        for($i = 1; $adapter->has($targetPath.'/'.$filename); $i++) {
            $fileparts = explode('.', $file['name']);
            if(is_array($fileparts)) {
                $fileparts[0] = $fileparts[0] . '_' . $i;
                $filename = implode('.', $fileparts);
            }
        }

        return $adapter->write($targetPath.'/'.$filename, $contents);
    }
}