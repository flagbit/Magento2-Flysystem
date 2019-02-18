<?php
namespace Flagbit\Flysystem\Adapter;

use \League\Flysystem\FilesystemFactory;
use \League\Flysystem\AdapterInterface as FlysystemAdapter;
use \Magento\Framework\ObjectManagerInterface;

/**
 * Class FilesystemAdapterFactory
 * @package Flagbit\Flysystem\Adapter
 */
class FilesystemAdapterFactory
{
    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var FilesystemFactory
     */
    protected $filesystemFactory;

    /**
     * FilesystemAdapterFactory constructor.
     * @param ObjectManagerInterface $objectManager
     * @param FilesystemFactory $filesystemFactory
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        FilesystemFactory $filesystemFactory
    ) {
        $this->objectManager = $objectManager;
        $this->filesystemFactory = $filesystemFactory;
    }

    /**
     * @param FlysystemAdapter $adapter
     * @param \League\Flysystem\Config|array|null $config
     * @return FilesystemAdapter
     */
    public function create(FlysystemAdapter $adapter, $config = null): FilesystemAdapter
    {
        $fileSystemObject = $this->filesystemFactory->create(
            [
                'adapter' => $adapter,
                'config' => $config
            ]
        );

        return $this->objectManager->create(
            FilesystemAdapter::class,
            [
                'filesystem' => $fileSystemObject
            ]
        );
    }
}