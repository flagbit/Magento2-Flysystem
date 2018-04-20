<?php
namespace Flagbit\Flysystem\Adapter;

use \League\Flysystem\Adapter\Local as LocalAdapter;
use \League\Flysystem\Adapter\Ftp as FtpAdapter;
use \League\Flysystem\Adapter\NullAdapter as NullAdapter;

use \Magento\Framework\App\Config\ScopeConfigInterface;
use \Magento\Framework\Event\Manager;
use \Magento\Framework\ObjectManagerInterface;

/**
 * Class FilesystemManager
 * @package Flagbit\Flysystem\Adapter
 */
class FilesystemManager implements ManagerInterface
{
    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var \Flagbit\Flysystem\Adapter\FilesystemAdapterFactory
     */
    protected $adapterFactory;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var Manager
     */
    protected $eventManager;

    /**
     * FilesystemManager constructor.
     * @param ObjectManagerInterface $objectManager
     * @param \Flagbit\Flysystem\Adapter\FilesystemAdapterFactory $adapter
     * @param ScopeConfigInterface $scopeConfig
     * @param Manager $eventManager
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        FilesystemAdapterFactory $adapter,
        ScopeConfigInterface $scopeConfig,
        Manager $eventManager
    ) {
        $this->objectManager = $objectManager;
        $this->adapterFactory = $adapter;
        $this->scopeConfig = $scopeConfig;
        $this->eventManager = $eventManager;
    }

    /**
     * @param array $config
     * @return mixed
     */
    public function createFtpDriver(array $config)
    {
        return $this->objectManager->create(FtpAdapter::class, [
            'config' => $config
        ]);
    }

    /**
     * @param $root
     * @param int $writeFlags
     * @param int $linkHandling
     * @param array $permissions
     * @return mixed
     */
    public function createLocalDriver($root, $writeFlags = LOCK_EX, $linkHandling = LocalAdapter::DISALLOW_LINKS, array $permissions = [])
    {
        return $this->objectManager->create(LocalAdapter::class, [
            'root' => $root,
            'writeFlags' => $writeFlags,
            'linkHandling' => $linkHandling,
            'permissions' => $permissions
        ]);
    }

    /**
     * @return mixed
     */
    public function createNullDriver()
    {
        return $this->objectManager->create(NullAdapter::class);
    }
}