<?php
namespace Flagbit\Flysystem\Adapter;

use \League\Flysystem\Filesystem;

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
     * @param string $path
     * @return bool
     */
    public function has($path)
    {
        return $this->filesystem->has($path);
    }

    /**
     * @param string $path
     * @param string $contents
     * @param array $config
     * @return bool
     */
    public function write($path, $contents, array $config = [])
    {
        return $this->filesystem->write($path, $contents, $config);
    }

    /**
     * @param string $path
     * @param resource $resource
     * @param array $config
     * @return bool
     */
    public function writeStream($path, $resource, array $config = [])
    {
        return $this->filesystem->writeStream($path, $resource, $config);
    }

    /**
     * @param string $path
     * @param string $contents
     * @param array $config
     * @return bool
     */
    public function put($path, $contents, array $config = [])
    {
        return $this->filesystem->put($path, $contents, $config);
    }

    /**
     * @param string $path
     * @param resource $resource
     * @param array $config
     * @return bool
     */
    public function putStream($path, $resource, array $config = [])
    {
        return $this->filesystem->putStream($path, $resource, $config);
    }

    /**
     * @param string $path
     * @return bool|false|string
     */
    public function readAndDelete($path)
    {
        return $this->filesystem->readAndDelete($path);
    }

    /**
     * @param string $path
     * @param string $contents
     * @param array $config
     * @return bool
     */
    public function update($path, $contents, array $config = [])
    {
        return $this->filesystem->update($path, $contents, $config);
    }

    /**
     * @param string $path
     * @param resource $resource
     * @param array $config
     * @return bool
     */
    public function updateStream($path, $resource, array $config = [])
    {
        return $this->filesystem->updateStream($path, $resource, $config);
    }

    /**
     * @param string $path
     * @return bool|false|string
     */
    public function read($path)
    {
        return $this->filesystem->read($path);
    }

    /**
     * @param string $path
     * @return bool|false|resource
     */
    public function readStream($path)
    {
        return $this->filesystem->readStream($path);
    }

    /**
     * @param string $path
     * @param string $newpath
     * @return bool
     */
    public function rename($path, $newpath)
    {
        return $this->filesystem->rename($path, $newpath);
    }

    /**
     * @param string $path
     * @param string $newpath
     * @return bool
     */
    public function copy($path, $newpath)
    {
        return $this->filesystem->copy($path, $newpath);
    }

    /**
     * @param string $path
     * @return bool
     */
    public function delete($path)
    {
        return $this->filesystem->delete($path);
    }

    /**
     * @param string $dirname
     * @return bool
     */
    public function deleteDir($dirname)
    {
        return $this->filesystem->deleteDir($dirname);
    }

    /**
     * @param string $dirname
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
     * @param string $path
     * @return bool|false|string
     */
    public function getMimetype($path)
    {
        return $this->filesystem->getMimetype($path);
    }

    /**
     * @param string $path
     * @return bool|false|string
     */
    public function getTimestamp($path)
    {
        return $this->filesystem->getTimestamp($path);
    }

    /**
     * @param string $path
     * @return bool|false|string
     */
    public function getVisibility($path)
    {
        return $this->filesystem->getVisibility($path);
    }

    /**
     * @param string $path
     * @return bool|false|int
     */
    public function getSize($path)
    {
        return $this->filesystem->getSize($path);
    }

    /**
     * @param string $path
     * @param string $visibility
     * @return bool
     */
    public function setVisibility($path, $visibility)
    {
        return $this->filesystem->setVisibility($path, $visibility);
    }

    /**
     * @param string $path
     * @return array|false
     */
    public function getMetadata($path)
    {
        return $this->filesystem->getMetadata($path);
    }

    /**
     * @param string $path
     * @return void
     */
    public function assertPresent($path)
    {
        $this->filesystem->assertPresent($path);
    }

    /**
     * @param string $path
     * @return void
     */
    public function assertAbsent($path)
    {
        $this->filesystem->assertAbsent($path);
    }
}