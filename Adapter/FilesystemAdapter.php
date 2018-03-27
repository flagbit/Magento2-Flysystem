<?php
namespace Flagbit\Flysystem\Adapter;

use \League\Flysystem\Filesystem;
use \League\Flysystem\Handler as FlysystemHandler;

class FilesystemAdapter implements AdapterInterface
{
    protected $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function has($path)
    {
        return $this->filesystem->has($path);
    }

    public function write($path, $contents, array $config = [])
    {
        return $this->filesystem->write($path, $contents, $config);
    }

    public function writeStream($path, $resource, array $config = [])
    {
        return $this->filesystem->writeStream($path, $resource, $config);
    }

    public function put($path, $contents, array $config = [])
    {
        return $this->filesystem->put($path, $contents, $config);
    }

    public function putStream($path, $resource, array $config = [])
    {
        return $this->filesystem->putStream($path, $resource, $config);
    }

    public function readAndDelete($path)
    {
        return $this->filesystem->readAndDelete($path);
    }

    public function update($path, $contents, array $config = [])
    {
        return $this->filesystem->update($path, $contents, $config);
    }

    public function updateStream($path, $resource, array $config = [])
    {
        return $this->filesystem->updateStream($path, $resource, $config);
    }

    public function read($path)
    {
        return $this->filesystem->read($path);
    }

    public function readStream($path)
    {
        return $this->filesystem->readStream($path);
    }

    public function rename($path, $newpath)
    {
        return $this->filesystem->rename($path, $newpath);
    }

    public function copy($path, $newpath)
    {
        return $this->filesystem->copy($path, $newpath);
    }

    public function delete($path)
    {
        return $this->filesystem->delete($path);
    }

    public function deleteDir($dirname)
    {
        return $this->filesystem->deleteDir($dirname);
    }

    public function createDir($dirname, array $config = [])
    {
        return $this->filesystem->createDir($dirname, $config);
    }

    public function listContents($directory = '', $recursive = false)
    {
        return $this->filesystem->listContents($directory, $recursive);
    }

    public function getMimetype($path)
    {
        return $this->filesystem->getMimetype($path);
    }

    public function getTimestamp($path)
    {
        return $this->filesystem->getTimestamp($path);
    }

    public function getVisibility($path)
    {
        return $this->filesystem->getVisibility($path);
    }

    public function getSize($path)
    {
        return $this->filesystem->getSize($path);
    }

    public function setVisibility($path, $visibility)
    {
        return $this->filesystem->setVisibility($path, $visibility);
    }

    public function getMetadata($path)
    {
        return $this->filesystem->getMetadata($path);
    }

    public function get($path, FlysystemHandler $handler = null)
    {
        return $this->filesystem->get($path, $handler);
    }

    public function assertPresent($path)
    {
        $this->filesystem->assertPresent($path);
    }

    public function assertAbsent($path)
    {
        $this->filesystem->assertAbsent($path);
    }
}