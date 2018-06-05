<?php
namespace Flagbit\Flysystem\Model\Filesystem;

use \Flagbit\Flysystem\Adapter\FilesystemAdapter;
use \Flagbit\Flysystem\Adapter\FilesystemAdapterFactory;
use \Flagbit\Flysystem\Adapter\FilesystemManager;
use \Flagbit\Flysystem\Helper\Config;
use \Magento\Framework\ObjectManagerInterface;
use \Magento\MediaStorage\Model\File\Uploader;
use \Psr\Log\LoggerInterface;

class UploadManager
{
    const SERVER_TMP_PATH = '/tmp';

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
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var null|Uploader
     */
    protected $uploader;

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var null|FilesystemAdapter
     */
    protected $adapter;

    /**
     * UploadManager constructor.
     * @param FilesystemManager $filesystemManager
     * @param FilesystemAdapterFactory $filesystemAdapterFactory
     * @param Config $flysystemConfig
     * @param LoggerInterface $logger
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(
        FilesystemManager $filesystemManager,
        FilesystemAdapterFactory $filesystemAdapterFactory,
        Config $flysystemConfig,
        LoggerInterface $logger,
        ObjectManagerInterface $objectManager
    ) {
        $this->flysystemManager = $filesystemManager;
        $this->flysystemFactory = $filesystemAdapterFactory;
        $this->flysystemConfig = $flysystemConfig;
        $this->logger = $logger;
        $this->objectManager = $objectManager;

        $this->create();
        $this->setUploadFile();
    }

    /**
     * @return FilesystemAdapter|mixed|null
     */
    public function create()
    {
        if(!$this->adapter) {
            $this->adapter = $this->flysystemFactory->create($this->flysystemManager->createLocalDriver(self::SERVER_TMP_PATH));
        }
        return $this->adapter;
    }

    /**
     * @return Uploader|null
     */
    public function getUploader()
    {
        return $this->uploader;
    }

    /**
     * @return FilesystemAdapter|null
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param string $fileId
     * @return bool
     */
    public function setUploadFile($fileId = \Flagbit\Flysystem\Helper\Config::FLYSYSTEM_UPLOAD_ID)
    {
        try {
            $this->uploader = $this->objectManager->create(Uploader::class, ['fileId' => $fileId]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param $file
     * @throws \Exception
     */
    public function validateFileType($file)
    {
        if(isset($file['name'])) {
            $parts = explode('.', $file['name']);
            $supportedFileTypes = $this->flysystemConfig->getSupportedFileTypes();

            if(in_array($parts[count($parts)-1], $supportedFileTypes)) {
                return;
            }
        }

        throw new \Exception('File type is not allowed');
    }

    /**
     * @param FilesystemAdapter $adapter
     * @param string $targetPath
     * @return mixed
     * @throws \Exception
     */
    public function upload($adapter, $targetPath)
    {
        try {
            $file = $this->getUploader()->validateFile();

            $this->validateFileType($file);

            if(!isset($file['tmp_name'])) {
                throw new \Exception('File not found!');
            }

            $contents = $this->getAdapter()->read(basename($file['tmp_name']));
            $filename = $file['name'];

            for($i = 1; $adapter->has($targetPath.'/'.$filename); $i++) {
                $fileparts = explode('.', $file['name']);
                if(is_array($fileparts)) {
                    $fileparts[0] = $fileparts[0] . '_' . $i;
                    $filename = implode('.', $fileparts);
                } else {
                    break;
                }
            }

            return $adapter->write($targetPath.'/'.$filename, $contents);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}