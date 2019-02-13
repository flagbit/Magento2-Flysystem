<?php
namespace Flagbit\Flysystem\Adapter;

use \League\Flysystem\Adapter\Local as LocalAdapter;
use \League\Flysystem\Adapter\LocalFactory as LocalAdapterFactory;
use \League\Flysystem\Adapter\Ftp as FtpAdapter;
use \League\Flysystem\Adapter\FtpFactory as FtpAdapterFactory;
use \League\Flysystem\Adapter\NullAdapter as NullAdapter;
use \League\Flysystem\Adapter\NullAdapterFactory as NullAdapterFactory;
use \League\Flysystem\Sftp\SftpAdapter;
use \League\Flysystem\Sftp\SftpAdapterFactory;

/**
 * Class FilesystemManager
 * @package Flagbit\Flysystem\Adapter
 */
class FilesystemManager implements ManagerInterface
{
    /**
     * @var LocalAdapterFactory
     */
    protected $localAdapterFactory;

    /**
     * @var FtpAdapterFactory
     */
    protected $ftpAdapterFactory;

    /**
     * @var NullAdapterFactory
     */
    protected $nullAdapterFactory;

    /**
     * @var SftpAdapterFactory
     */
    protected $sftpAdapterFactory;

    /**
     * FilesystemManager constructor.
     * @param LocalAdapterFactory $localAdapterFactory
     * @param FtpAdapterFactory $ftpAdapterFactory
     * @param NullAdapterFactory $nullAdapterFactory
     * @param SftpAdapterFactory $sftpAdapterFactory
     */
    public function __construct(
        LocalAdapterFactory $localAdapterFactory,
        FtpAdapterFactory $ftpAdapterFactory,
        NullAdapterFactory $nullAdapterFactory,
        SftpAdapterFactory $sftpAdapterFactory
    ) {
        $this->localAdapterFactory = $localAdapterFactory;
        $this->ftpAdapterFactory = $ftpAdapterFactory;
        $this->nullAdapterFactory = $nullAdapterFactory;
        $this->sftpAdapterFactory = $sftpAdapterFactory;
    }

    /**
     * @param array $config
     * @return SftpAdapter
     */
    public function createSftpDriver(array $config): SftpAdapter
    {
        return $this->sftpAdapterFactory->create([
            'config' => $config
        ]);
    }

    /**
     * @param array $config
     * @return FtpAdapter
     */
    public function createFtpDriver(array $config): FtpAdapter
    {
        return $this->ftpAdapterFactory->create([
            'config' => $config
        ]);
    }

    /**
     * @param string $root
     * @param int $writeFlags
     * @param int $linkHandling
     * @param array $permissions
     * @return LocalAdapter
     */
    public function createLocalDriver(string $root, int $writeFlags = LOCK_EX, int $linkHandling = LocalAdapter::SKIP_LINKS, array $permissions = []): LocalAdapter
    {
        return $this->localAdapterFactory->create([
            'root' => $root,
            'writeFlags' => $writeFlags,
            'linkHandling' => $linkHandling,
            'permissions' => $permissions
        ]);
    }

    /**
     * @return NullAdapter
     */
    public function createNullDriver(): NullAdapter
    {
        return $this->nullAdapterFactory->create([]);
    }
}