<?php
namespace Flagbit\Flysystem\Adapter;

/**
 * Interface AdapterInterface
 * @package Flagbit\Flysystem\Adapter
 */
interface AdapterInterface {

    /**
     * @param string $path
     * @return bool
     */
    public function has(string $path): bool;

    /**
     * @param string $path
     * @param string $contents
     * @param array $config
     * @return bool
     */
    public function write(string $path, $contents, array $config = []): bool;

    /**
     * @param string $path
     * @param resource $resource
     * @param array $config
     * @return bool
     */
    public function writeStream(string $path, $resource, array $config = []): bool;

    /**
     * @param string $path
     * @param string $contents
     * @param array $config
     * @return bool
     */
    public function put(string $path, $contents, array $config = []): bool;

    /**
     * @param string $path
     * @param resource $resource
     * @param array $config
     * @return bool
     */
    public function putStream(string $path, $resource, array $config = []): bool;

    /**
     * @param string $path
     * @return string|false
     */
    public function readAndDelete(string $path);

    /**
     * @param string $path
     * @param string $contents
     * @param array $config
     * @return bool
     */
    public function update(string $path, $contents, array $config = []): bool;

    /**
     * @param string $path
     * @param resource $resource
     * @param array $config
     * @return bool
     */
    public function updateStream(string $path, $resource, array $config = []): bool;

    /**
     * @param string $path
     * @return string|false
     */
    public function read(string $path);

    /**
     * @param string $path
     * @return array|false
     */
    public function readStream(string $path);

    /**
     * @param string $path
     * @param string $newpath
     * @return bool
     */
    public function rename(string $path, string $newpath): bool;

    /**
     * @param string $path
     * @param string $newpath
     * @return bool
     */
    public function copy(string $path, string $newpath): bool;

    /**
     * @param string $path
     * @return bool
     */
    public function delete(string $path): bool;

    /**
     * @param string $dirname
     * @return bool
     */
    public function deleteDir(string $dirname): bool;

    /**
     * @param string $dirname
     * @param array $config
     * @return bool
     */
    public function createDir(string $dirname, array $config = []): bool;

    /**
     * @param string $directory
     * @param bool $recursive
     * @return array|null
     */
    public function listContents(string $directory = '', bool $recursive = false);

    /**
     * @param string $path
     * @return string|false
     */
    public function getMimetype(string $path);

    /**
     * @param string $path
     * @return string|false
     */
    public function getTimestamp(string $path);

    /**
     * @param string $path
     * @return string|false
     */
    public function getVisibility(string $path);

    /**
     * @param string $path
     * @return int|false
     */
    public function getSize(string $path);

    /**
     * @param string $path
     * @param string $visibility
     * @return bool
     */
    public function setVisibility(string $path, string $visibility): bool;

    /**
     * @param string $path
     * @return array|false
     */
    public function getMetadata(string $path);

    /**
     * @param string $path
     * @return void
     */
    public function assertPresent(string $path);

    /**
     * @param string $path
     * @return void
     */
    public function assertAbsent(string $path);
}