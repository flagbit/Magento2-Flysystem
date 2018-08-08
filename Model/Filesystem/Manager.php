<?php
namespace Flagbit\Flysystem\Model\Filesystem;

use \Flagbit\Flysystem\Adapter\FilesystemAdapter;
use \Flagbit\Flysystem\Adapter\FilesystemAdapterFactory;
use \Flagbit\Flysystem\Adapter\FilesystemManager;
use \Flagbit\Flysystem\Helper\Config;
use \Flagbit\Flysystem\Helper\Errors;
use \Magento\Backend\Model\Session;
use \Magento\Framework\Event\Manager as EventManager;
use \Magento\Framework\Exception\LocalizedException;
use \Psr\Log\LoggerInterface;

/**
 * Class Manager
 * @package Flagbit\Flysystem\Model\Filesystem
 */
class Manager
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
     * @var EventManager
     */
    protected $_eventManager;

    /**
     * @var Config
     */
    protected $_flysystemConfig;

    /**
     * @var Session
     */
    protected $_session;

    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * @var null|FilesystemAdapter
     */
    protected $_adapter;

    /**
     * @var string
     */
    protected $_path;

    /**
     * Manager constructor.
     * @param FilesystemManager $flysystemManager
     * @param FilesystemAdapterFactory $flysystemFactory
     * @param EventManager $eventManager
     * @param Config $flysystemConfig
     * @param Session $session
     * @param LoggerInterface $logger
     */
    public function __construct(
        FilesystemManager $flysystemManager,
        FilesystemAdapterFactory $flysystemFactory,
        EventManager $eventManager,
        Config $flysystemConfig,
        Session $session,
        LoggerInterface $logger
    ) {
        $this->_flysystemManager = $flysystemManager;
        $this->_flysystemFactory = $flysystemFactory;
        $this->_eventManager = $eventManager;
        $this->_flysystemConfig = $flysystemConfig;
        $this->_session = $session;
        $this->_logger = $logger;
    }

    /**
     * @param null|string $source
     * @return FilesystemAdapter|null
     */
    public function create($source = null)
    {
        if(!$source) {
            $source = $this->_flysystemConfig->getSource();
        }

        switch($source) {
            case 'local':
                $this->setAdapter($this->createLocalAdapter());
                break;
            case 'ftp':
                $this->setAdapter($this->createFtpAdapter());
                break;
            case 'sftp':
                $this->setAdapter($this->createSftpAdapter());
                break;
            case 'test':
                $this->setAdapter($this->createNullAdapter());
                break;
        }

        $this->_eventManager->dispatch('flagbit_flysystem_create_after', ['source' => $source, 'manager' => $this]);

        return $this->getAdapter(false);
    }

    /**
     * @param bool $createIfNotExists
     * @return FilesystemAdapter|null
     * @throws LocalizedException
     */
    public function getAdapter($createIfNotExists = true)
    {
        if(!$this->_adapter && $createIfNotExists) {
            $this->create();
        }

        if(!$this->_adapter) {
            throw new LocalizedException(Errors::getErrorMessage(111));
        }
        return $this->_adapter;
    }

    /**
     * @param FilesystemAdapter|null $adapter
     */
    public function setAdapter($adapter)
    {
        $this->_adapter = $adapter;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->_path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->_path = $path;
    }

    /**
     * @param null|string $path
     * @return mixed|null
     */
    public function createLocalAdapter($path = null)
    {
        try {
            if(empty($path)) {
                $path = $this->_flysystemConfig->getLocalPath();
                if (empty($path)) {
                    $path = '/';
                }
            }

            $this->setPath($path);

            return $this->_flysystemFactory->create($this->_flysystemManager->createLocalDriver($path));
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
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
            $host = $this->_flysystemConfig->getFtpHost();
            $user = $this->_flysystemConfig->getFtpUser();
            $password = $this->_flysystemConfig->getFtpPassword();
            if(empty($host) || empty($user) || empty($password)) {
                throw new LocalizedException(Errors::getErrorMessage(101));
            }

            $ftpPath = $this->_flysystemConfig->getFtpPath();
            if(empty($ftpPath)) {
                $ftpPath = '/';
            }

            $this->setPath($ftpPath);

            return $this->_flysystemFactory->create($this->_flysystemManager->createFtpDriver([
                'host' => $host,
                'username' => $user,
                'password' => $password,
                'port' => $this->_flysystemConfig->getFtpPort(),
                'root' => $ftpPath,
                'passive' => $this->_flysystemConfig->getFtpPassive(),
                'ssl' => $this->_flysystemConfig->getFtpSsl(),
                'timeout' => $this->_flysystemConfig->getFtpTimeout()
            ]));
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
            return null;
        }
    }

    /**
     * @return mixed
     * @throws LocalizedException
     */
    protected function createSftpAdapter()
    {
        try {
            $host = $this->_flysystemConfig->getSftpHost();
            $username = $this->_flysystemConfig->getSftpUsername()();
            $password = $this->_flysystemConfig->getSftpPassword();
            $privateKeyPathOrContent = $this->_flysystemConfig->getSftpPrivateKeyPathOrContent();

            if(empty($host) || empty($username) || (empty($password) || empty($privateKeyPathOrContent))) {
                throw new LocalizedException(Errors::getErrorMessage(121));
            }

            $sftpRoot = $this->_flysystemConfig->getSftpRoot();
            if(empty($sftpRoot)) {
                $sftpRoot = '/';
            }

            $this->setPath($sftpRoot);

            return $this->_flysystemFactory->create($this->_flysystemManager->createSftpDriver([
                'host' => $host,
                'port' => $this->_flysystemConfig->getSftpPort(),
                'username' => $username,
                'password' => $password,
                'privateKey' => $this->_flysystemConfig->getSftpPrivateKeyPathOrContent(),
                'root' => $this->_flysystemConfig->getSftpRoot(),
                'timeout' => $this->_flysystemConfig->getSftpTimeout(),
                'directoryPerm' => $this->_flysystemConfig->getSftpDirectoryPermissions(),
            ]));
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
            return null;
        }
    }


    /**
     * @return mixed
     */
    protected function createNullAdapter()
    {
        $driver = $this->_flysystemManager->createNullDriver();
        return $this->_flysystemFactory->create($driver);
    }

    /**
     * @return Session
     */
    public function getSession()
    {
        return $this->_session;
    }

    /**
     * @param string $identifier
     * @return mixed
     */
    public function setModalIdentifier($identifier)
    {
        return $this->getSession()->setFlysystemModalId($identifier);
    }

    /**
     * @return mixed
     */
    public function getModalIdentifier()
    {
        return $this->getSession()->getFlysystemModalId();
    }
}