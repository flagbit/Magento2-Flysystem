<?php
namespace Flagbit\Flysystem\Model\Filesystem;

use \Flagbit\Flysystem\Adapter\FilesystemAdapter;
use \Flagbit\Flysystem\Adapter\FilesystemAdapterFactory;
use \Flagbit\Flysystem\Adapter\FilesystemManager;
use \Flagbit\Flysystem\Helper\Config;
use \Magento\Backend\Model\Session;
use \Magento\Framework\Event\Manager as EventManager;
use \Magento\Framework\Exception\LocalizedException;
use \Psr\Log\LoggerInterface;

class Manager
{
    /**
     * @var FilesystemManager
     */
    protected $filesystemManager;

    /**
     * @var FilesystemAdapterFactory
     */
    protected $filesystemFactory;

    /**
     * @var EventManager
     */
    protected $eventManager;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var null|FilesystemAdapter
     */
    protected $adapter;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var string
     */
    protected $path;

    /**
     * Manager constructor.
     * @param FilesystemManager $filesystemManager
     * @param FilesystemAdapterFactory $filesystemAdapterFactory
     * @param EventManager $eventManager
     * @param Config $config
     */
    public function __construct(
        FilesystemManager $filesystemManager,
        FilesystemAdapterFactory $filesystemAdapterFactory,
        EventManager $eventManager,
        Config $config,
        Session $session,
        LoggerInterface $logger
    ) {
        $this->filesystemManager = $filesystemManager;
        $this->filesystemFactory = $filesystemAdapterFactory;
        $this->eventManager = $eventManager;
        $this->config = $config;
        $this->session = $session;
        $this->logger = $logger;
    }

    /**
     * @param null $source
     * @return FilesystemAdapter|null
     */
    public function create($source = null)
    {
        if(!$source) {
            $source = $this->config->getSource();
        }

        switch($source) {
            case 'local':
                $this->setAdapter($this->createLocalAdapter());
                break;
            case 'ftp':
                $this->setAdapter($this->createFtpAdapter());
                break;
            case 'test':
                $this->setAdapter($this->createNullAdapter());
                break;
        }

        $this->eventManager->dispatch('flagbit_flysystem_create_after', ['source' => $source, 'manager' => $this]);

        return $this->getAdapter();
    }

    /**
     * @return FilesystemAdapter|null
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param FilesystemAdapter|null $adapter
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @param null|string $path
     * @return mixed|null
     */
    public function createLocalAdapter($path = null)
    {
        try {
            if(empty($path)) {
                $path = $this->config->getLocalPath();
                if (empty($path)) {
                    $path = '/';
                }
            }

            $this->setPath($path);

            return $this->filesystemFactory->create($this->filesystemManager->createLocalDriver($path));
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
            return null;
        }
    }

    /**
     * @return mixed
     * @throws LocalizedException
     */
    protected function createFtpAdapter()
    {
        try {
            $host = $this->config->getFtpHost();
            $user = $this->config->getFtpUser();
            $password = $this->config->getFtpPassword();
            if(empty($host) || empty($user) || empty($password)) {
                throw new LocalizedException(__('FTP connection is not possible. Please check your configuration.'));
            }

            $ftpPath = $this->config->getFtpPath();
            if(empty($ftpPath)) {
                $ftpPath = '/';
            }

            $this->setPath($ftpPath);

            return $this->filesystemFactory->create($this->filesystemManager->createFtpDriver([
                'host' => $host,
                'username' => $user,
                'password' => $password,
                'port' => $this->config->getFtpPort(),
                'root' => $ftpPath,
                'passive' => $this->config->getFtpPassive(),
                'ssl' => $this->config->getFtpSsl(),
                'timeout' => $this->config->getFtpTimeout()
            ]));
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
            return null;
        }
    }

    /**
     * @return mixed
     */
    protected function createNullAdapter()
    {
        $driver = $this->filesystemManager->createNullDriver();
        return $this->filesystemFactory->create($driver);
    }

    public function getSession()
    {
        return $this->session;
    }

    public function setModalIdentifier($identifier)
    {
        return $this->getSession()->setFlysystemModalId($identifier);
    }

    public function getModalIdentifier()
    {
        return $this->getSession()->getFlysystemModalId();
    }
}