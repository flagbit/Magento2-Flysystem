<?php
namespace Flagbit\Flysystem\Adapter;

use \Flagbit\Flysystem\Adapter\FilesystemAdapterFactory;
use \League\Flysystem\Adapter\Local as LocalAdapter;
use \League\Flysystem\Adapter\Ftp as FtpAdapter;
use \League\Flysystem\Adapter\NullAdapter as NullAdapter;

use Flagbit\Flysystem\Adapter\ManagerInterface\ManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\Manager;
use Magento\Framework\ObjectManagerInterface;

class FilesystemManager implements ManagerInterface
{
    protected $objectManager;

    protected $adapterFactory;

    protected $scopeConfig;

    protected $eventManager;

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

    public function create($source = null)
    {
        if(!$source) {
            $source = $this->scopeConfig->getValue('flagbit_flysystem/general/source');
        }

        $driver = null;

        $this->eventManager->dispatch('flagbit_flysystem_create_before', ['source' => $source, 'driver' => $driver]);

        return $driver;
    }

    public function createFtpDriver(array $config)
    {
        return $this->objectManager->create(FtpAdapter::class, [
            'config' => $config
        ]);
    }

    public function createLocalDriver($root, $writeFlags = LOCK_EX, $linkHandling = LocalAdapter::DISALLOW_LINKS, array $permissions = [])
    {
        return $this->objectManager->create(LocalAdapter::class, [
            'root' => $root,
            'writeFlags' => $writeFlags,
            'linkHandling' => $linkHandling,
            'permissions' => $permissions
        ]);
    }

    public function createNullDriver()
    {
        return $this->objectManager->create(NullAdapter::class);
    }
}