<?php
namespace Flagbit\Flysystem\Adapter;

use \League\Flysystem\Filesystem;
use \League\Flysystem\AdapterInterface as FlysystemAdapter;
use Magento\Framework\ObjectManagerInterface;

class FilesystemAdapterFactory
{
    public function __construct(
        ObjectManagerInterface $objectManager
    ) {
        $this->objectManager = $objectManager;
    }

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