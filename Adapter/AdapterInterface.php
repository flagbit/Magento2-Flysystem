<?php
namespace Flagbit\Flysystem\Adapter;

use \League\Flysystem\Handler as FlysystemHandler;

interface AdapterInterface {
    public function has($path);
    public function write($path, $contents, array $config = []);
    public function writeStream($path, $resource, array $config = []);
    public function put($path, $contents, array $config = []);
    public function putStream($path, $resource, array $config = []);
    public function readAndDelete($path);
    public function update($path, $contents, array $config = []);
    public function updateStream($path, $resource, array $config = []);
    public function read($path);
    public function readStream($path);
    public function rename($path, $newpath);
    public function copy($path, $newpath);
    public function delete($path);
    public function deleteDir($dirname);
    public function createDir($dirname, array $config = []);
    public function listContents($directory = '', $recursive = false);
    public function getMimetype($path);
    public function getTimestamp($path);
    public function getVisibility($path);
    public function getSize($path);
    public function setVisibility($path, $visibility);
    public function getMetadata($path);
    public function get($path, FlysystemHandler $handler = null);
    public function assertPresent($path);
    public function assertAbsent($path);
}