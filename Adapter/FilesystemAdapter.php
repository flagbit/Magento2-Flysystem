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
    public function has(string $path): bool
    {
        return $this->filesystem->has($path);
    }

    /**
     * @param string $path
     * @param string|null $contents
     * @param array $config
     * @return bool
     * @throws \League\Flysystem\FileExistsException
     */
    public function write(string $path, $contents, array $config = []): bool
    {
        return $this->filesystem->write($path, $contents, $config);
    }

    /**
     * @param string $path
     * @param resource $resource
     * @param array $config
     * @return bool
     * @throws \League\Flysystem\FileExistsException
     */
    public function writeStream(string $path, $resource, array $config = []): bool
    {
        return $this->filesystem->writeStream($path, $resource, $config);
    }

    /**
     * @param string $path
     * @param string|null $contents
     * @param array $config
     * @return bool
     */
    public function put(string $path, $contents, array $config = []): bool
    {
        return $this->filesystem->put($path, $contents, $config);
    }

    /**
     * @param string $path
     * @param resource $resource
     * @param array $config
     * @return bool
     */
    public function putStream(string $path, $resource, array $config = []): bool
    {
        return $this->filesystem->putStream($path, $resource, $config);
    }

    /**
     * @param string $path
     * @return string|false
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function readAndDelete(string $path)
    {
        return $this->filesystem->readAndDelete($path);
    }

    /**
     * @param string $path
     * @param string|null $contents
     * @param array $config
     * @return bool
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function update(string $path, $contents, array $config = []): bool
    {
        return $this->filesystem->update($path, $contents, $config);
    }

    /**
     * @param string $path
     * @param resource $resource
     * @param array $config
     * @return bool
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function updateStream(string $path, $resource, array $config = []): bool
    {
        return $this->filesystem->updateStream($path, $resource, $config);
    }

    /**
     * @param string $path
     * @return string|false
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function read(string $path)
    {
        return $this->filesystem->read($path);
    }

    /**
     * @param string $path
     * @return array|false
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function readStream(string $path)
    {
        return $this->filesystem->readStream($path);
    }

    /**
     * @param string $path
     * @param string $newpath
     * @return bool
     * @throws \League\Flysystem\FileExistsException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function rename(string $path, string $newpath): bool
    {
        return $this->filesystem->rename($path, $newpath);
    }

    /**
     * @param string $path
     * @param string $newpath
     * @return bool
     * @throws \League\Flysystem\FileExistsException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function copy(string $path, string $newpath): bool
    {
        return $this->filesystem->copy($path, $newpath);
    }

    /**
     * @param string $path
     * @return bool
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function delete(string $path): bool
    {
        return $this->filesystem->delete($path);
    }

    /**
     * @param string $dirname
     * @return bool
     */
    public function deleteDir(string $dirname): bool
    {
        return $this->filesystem->deleteDir($dirname);
    }

    /**
     * @param string $dirname
     * @param array $config
     * @return bool
     */
    public function createDir(string $dirname, array $config = []): bool
    {
        return $this->filesystem->createDir($dirname, $config);
    }

    /**
     * @param string $directory
     * @param bool $recursive
     * @return array|null
     */
    public function listContents(string $directory = '', bool $recursive = false)
    {
        return $this->filesystem->listContents($directory, $recursive);
    }

    /**
     * @param string $path
     * @return string|false
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function getMimetype(string $path)
    {
        return $this->filesystem->getMimetype($path);
    }

    /**
     * @param string $path
     * @return string|false
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function getTimestamp(string $path)
    {
        return $this->filesystem->getTimestamp($path);
    }

    /**
     * @param string $path
     * @return string|false
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function getVisibility(string $path)
    {
        return $this->filesystem->getVisibility($path);
    }

    /**
     * @param string $path
     * @return int|false
     */
    public function getSize(string $path)
    {
        return $this->filesystem->getSize($path);
    }

    /**
     * @param string $path
     * @param string $visibility
     * @return bool
     */
    public function setVisibility(string $path, string $visibility): bool
    {
        return $this->filesystem->setVisibility($path, $visibility);
    }

    /**
     * @param string $path
     * @return array|false
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function getMetadata(string $path)
    {
        return $this->filesystem->getMetadata($path);
    }

    /**
     * @param string $path
     * @return void
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function assertPresent(string $path)
    {
        $this->filesystem->assertPresent($path);
    }

    /**
     * @param string $path
     * @return void
     * @throws \League\Flysystem\FileExistsException
     */
    public function assertAbsent(string $path)
    {
        $this->filesystem->assertAbsent($path);
    }
}