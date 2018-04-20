<?php
namespace Flagbit\Flysystem\Adapter;

use \League\Flysystem\Filesystem;
use \League\Flysystem\AdapterInterface as FlysystemAdapter;
use \Magento\Framework\ObjectManagerInterface;

/**
 * Class FilesystemAdapterFactory
 * @package Flagbit\Flysystem\Adapter
 */
class FilesystemAdapterFactory
{

    /**
     * FilesystemAdapterFactory constructor.
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(
        ObjectManagerInterface $objectManager
    ) {
        $this->objectManager = $objectManager;
    }

    /**
     * @param FlysystemAdapter $adapter
     * @param null $config
     * @return mixed
     */
    public function create(FlysystemAdapter $adapter, $config = null)
    {
        $fileSystemObject = $this->objectManager->create(
            Filesystem::class,
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