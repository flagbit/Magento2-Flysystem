<?php
namespace Flagbit\Flysystem\Adapter;

use \League\Flysystem\Adapter\Local as LocalAdapter;
use \League\Flysystem\Adapter\Ftp as FtpAdapter;
use \League\Flysystem\Sftp\SftpAdapter;
use \League\Flysystem\Adapter\NullAdapter as NullAdapter;

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
     * FilesystemManager constructor.
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(
        ObjectManagerInterface $objectManager
    ) {
        $this->objectManager = $objectManager;
    }

    /**
     * @param array $config
     * @return mixed
     */
    public function createSftpDriver(array $config)
    {
        return $this->objectManager->create(SftpAdapter::class, [
            'config' => $config
        ]);
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
     * @param string $root
     * @param int $writeFlags
     * @param int $linkHandling
     * @param array $permissions
     * @return mixed
     */
    public function createLocalDriver($root, $writeFlags = LOCK_EX, $linkHandling = LocalAdapter::SKIP_LINKS, array $permissions = [])
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