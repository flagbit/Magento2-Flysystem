<?php
namespace Flagbit\Flysystem\Adapter;

/**
 * Interface AdapterInterface
 * @package Flagbit\Flysystem\Adapter
 */
interface AdapterInterface {

    /**
     * @param $path
     * @return mixed
     */
    public function has($path);

    /**
     * @param $path
     * @param $contents
     * @param array $config
     * @return mixed
     */
    public function write($path, $contents, array $config = []);

    /**
     * @param $path
     * @param $resource
     * @param array $config
     * @return mixed
     */
    public function writeStream($path, $resource, array $config = []);

    /**
     * @param $path
     * @param $contents
     * @param array $config
     * @return mixed
     */
    public function put($path, $contents, array $config = []);

    /**
     * @param $path
     * @param $resource
     * @param array $config
     * @return mixed
     */
    public function putStream($path, $resource, array $config = []);

    /**
     * @param $path
     * @return mixed
     */
    public function readAndDelete($path);

    /**
     * @param $path
     * @param $contents
     * @param array $config
     * @return mixed
     */
    public function update($path, $contents, array $config = []);

    /**
     * @param $path
     * @param $resource
     * @param array $config
     * @return mixed
     */
    public function updateStream($path, $resource, array $config = []);

    /**
     * @param $path
     * @return mixed
     */
    public function read($path);

    /**
     * @param $path
     * @return mixed
     */
    public function readStream($path);

    /**
     * @param $path
     * @param $newpath
     * @return mixed
     */
    public function rename($path, $newpath);

    /**
     * @param $path
     * @param $newpath
     * @return mixed
     */
    public function copy($path, $newpath);

    /**
     * @param $path
     * @return mixed
     */
    public function delete($path);

    /**
     * @param $dirname
     * @return mixed
     */
    public function deleteDir($dirname);

    /**
     * @param $dirname
     * @param array $config
     * @return mixed
     */
    public function createDir($dirname, array $config = []);

    /**
     * @param string $directory
     * @param bool $recursive
     * @return mixed
     */
    public function listContents($directory = '', $recursive = false);

    /**
     * @param $path
     * @return mixed
     */
    public function getMimetype($path);

    /**
     * @param $path
     * @return mixed
     */
    public function getTimestamp($path);

    /**
     * @param $path
     * @return mixed
     */
    public function getVisibility($path);

    /**
     * @param $path
     * @return mixed
     */
    public function getSize($path);

    /**
     * @param $path
     * @param $visibility
     * @return mixed
     */
    public function setVisibility($path, $visibility);

    /**
     * @param $path
     * @return mixed
     */
    public function getMetadata($path);

    /**
     * @param $path
     * @return mixed
     */
    public function assertPresent($path);

    /**
     * @param $path
     * @return mixed
     */
    public function assertAbsent($path);
}