<?php
namespace Flagbit\Flysystem\Model\Filesystem;

use \Flagbit\Flysystem\Adapter\FilesystemAdapter;
use \Flagbit\Flysystem\Adapter\FilesystemAdapterFactory;
use \Flagbit\Flysystem\Adapter\FilesystemManager;
use \Magento\Framework\ObjectManagerInterface;
use \Magento\MediaStorage\Model\File\Uploader;
use \Psr\Log\LoggerInterface;

class UploadManager
{
    const SERVER_TMP_PATH = '/tmp';

    protected $flysystemManager;

    protected $flysystemFactory;

    protected $logger;

    protected $uploader;

    protected $objectManager;

    /**
     * @var null|FilesystemAdapter
     */
    protected $adapter;

    public function __construct(
        FilesystemManager $filesystemManager,
        FilesystemAdapterFactory $filesystemAdapterFactory,
        LoggerInterface $logger,
        ObjectManagerInterface $objectManager
    ) {
        $this->flysystemManager = $filesystemManager;
        $this->flysystemFactory = $filesystemAdapterFactory;
        $this->logger = $logger;
        $this->objectManager = $objectManager;

        $this->create();
        $this->setUploadFile();
    }

    public function create()
    {
        if(!$this->adapter) {
            $this->adapter = $this->flysystemFactory->create($this->flysystemManager->createLocalDriver(self::SERVER_TMP_PATH));
        }
        return $this->adapter;
    }

    public function getUploader()
    {
        return $this->uploader;
    }

    public function getAdapter()
    {
        return $this->adapter;
    }

    public function setUploadFile($fileId = \Flagbit\Flysystem\Helper\Config::FLYSYSTEM_UPLOAD_ID)
    {
        try {
            $this->uploader = $this->objectManager->create(Uploader::class, ['fileId' => $fileId]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function upload($adapter, $targetPath)
    {
        try {
            $file = $this->getUploader()->validateFile();

            if(!isset($file['tmp_name'])) {
                throw new \Exception('File not found!');
            }

            $contents = $this->getAdapter()->read(basename($file['tmp_name']));
            $filename = $file['name'];

            return $adapter->write($targetPath.'/'.$filename, $contents);
        } catch (\Exception $e) {
            return false;
        }
    }
}