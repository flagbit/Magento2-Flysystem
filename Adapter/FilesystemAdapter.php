<?php
namespace Flagbit\Flysystem\Adapter;

use \League\Flysystem\Filesystem;
use \League\Flysystem\Handler as FlysystemHandler;

/**
 * Class FilesystemAdapter
 * @package Flagbit\Flysystem\Adapter
 */
class FilesystemAdapter implements AdapterInterface
{
    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * FilesystemAdapter constructor.
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param $path
     * @return bool
     */
    public function has($path)
    {
        return $this->filesystem->has($path);
    }

    /**
     * @param $path
     * @param $contents
     * @param array $config
     * @return bool
     */
    public function write($path, $contents, array $config = [])
    {
        return $this->filesystem->write($path, $contents, $config);
    }

    /**
     * @param $path
     * @param $resource
     * @param array $config
     * @return bool
     */
    public function writeStream($path, $resource, array $config = [])
    {
        return $this->filesystem->writeStream($path, $resource, $config);
    }

    /**
     * @param $path
     * @param $contents
     * @param array $config
     * @return bool
     */
    public function put($path, $contents, array $config = [])
    {
        return $this->filesystem->put($path, $contents, $config);
    }

    /**
     * @param $path
     * @param $resource
     * @param array $config
     * @return bool
     */
    public function putStream($path, $resource, array $config = [])
    {
        return $this->filesystem->putStream($path, $resource, $config);
    }

    /**
     * @param $path
     * @return bool|false|string
     */
    public function readAndDelete($path)
    {
        return $this->filesystem->readAndDelete($path);
    }

    /**
     * @param $path
     * @param $contents
     * @param array $config
     * @return bool
     */
    public function update($path, $contents, array $config = [])
    {
        return $this->filesystem->update($path, $contents, $config);
    }

    /**
     * @param $path
     * @param $resource
     * @param array $config
     * @return bool
     */
    public function updateStream($path, $resource, array $config = [])
    {
        return $this->filesystem->updateStream($path, $resource, $config);
    }

    /**
     * @param $path
     * @return bool|false|string
     */
    public function read($path)
    {
        return $this->filesystem->read($path);
    }

    /**
     * @param $path
     * @return bool|false|resource
     */
    public function readStream($path)
    {
        return $this->filesystem->readStream($path);
    }

    /**
     * @param $path
     * @param $newpath
     * @return bool
     */
    public function rename($path, $newpath)
    {
        return $this->filesystem->rename($path, $newpath);
    }

    /**
     * @param $path
     * @param $newpath
     * @return bool
     */
    public function copy($path, $newpath)
    {
        return $this->filesystem->copy($path, $newpath);
    }

    /**
     * @param $path
     * @return bool
     */
    public function delete($path)
    {
        return $this->filesystem->delete($path);
    }

    /**
     * @param $dirname
     * @return bool
     */
    public function deleteDir($dirname)
    {
        return $this->filesystem->deleteDir($dirname);
    }

    /**
     * @param $dirname
     * @param array $config
     * @return bool
     */
    public function createDir($dirname, array $config = [])
    {
        return $this->filesystem->createDir($dirname, $config);
    }

    /**
     * @param string $directory
     * @param bool $recursive
     * @return array
     */
    public function listContents($directory = '', $recursive = false)
    {
        return $this->filesystem->listContents($directory, $recursive);
    }

    /**
     * @param $path
     * @return bool|false|string
     */
    public function getMimetype($path)
    {
        return $this->filesystem->getMimetype($path);
    }

    /**
     * @param $path
     * @return bool|false|string
     */
    public function getTimestamp($path)
    {
        return $this->filesystem->getTimestamp($path);
    }

    /**
     * @param $path
     * @return bool|false|string
     */
    public function getVisibility($path)
    {
        return $this->filesystem->getVisibility($path);
    }

    /**
     * @param $path
     * @return bool|false|int
     */
    public function getSize($path)
    {
        return $this->filesystem->getSize($path);
    }

    /**
     * @param $path
     * @param $visibility
     * @return bool
     */
    public function setVisibility($path, $visibility)
    {
        return $this->filesystem->setVisibility($path, $visibility);
    }

    /**
     * @param $path
     * @return array|false
     */
    public function getMetadata($path)
    {
        return $this->filesystem->getMetadata($path);
    }

    /**
     * @param $path
     * @param FlysystemHandler|null $handler
     * @return \League\Flysystem\Directory|\League\Flysystem\File|FlysystemHandler
     */
    public function get($path, FlysystemHandler $handler = null)
    {
        return $this->filesystem->get($path, $handler);
    }

    /**
     * @param $path
     * @return void
     */
    public function assertPresent($path)
    {
        $this->filesystem->assertPresent($path);
    }

    /**
     * @param $path
     * @return void
     */
    public function assertAbsent($path)
    {
        $this->filesystem->assertAbsent($path);
    }
}