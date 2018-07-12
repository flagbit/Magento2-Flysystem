<?php
namespace Flagbit\Flysystem\Test\Unit\Adapter;

use \Flagbit\Flysystem\Adapter\FilesystemAdapter;
use \League\Flysystem\Filesystem;
use \League\Flysystem\Handler;
use \PHPUnit\Framework\MockObject\MockObject;
use \PHPUnit\Framework\TestCase;

class FilesystemAdapterTest extends TestCase
{
    /**
     * @var Filesystem|MockObject
     */
    protected $_filesystemMock;

    /**
     * @var FilesystemAdapter
     */
    protected $_object;

    public function setUp()
    {
        $this->_filesystemMock = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'has',
                    'write',
                    'writeStream',
                    'put',
                    'putStream',
                    'readAndDelete',
                    'update',
                    'updateStream',
                    'read',
                    'readStream',
                    'rename',
                    'copy',
                    'delete',
                    'deleteDir',
                    'createDir',
                    'listContents',
                    'getMimetype',
                    'getTimestamp',
                    'getVisibility',
                    'getSize',
                    'setVisibility',
                    'getMetadata',
                    'assertPresent',
                    'assertAbsent'
                ]
            )
            ->getMock();

        $this->_object = new FilesystemAdapter(
            $this->_filesystemMock
        );
    }

    public function testHas()
    {
        $path = '/test/path';

        $this->_filesystemMock->expects($this->once())
            ->method('has')
            ->with($path)
            ->willReturn(true);

        $this->assertEquals(true, $this->_object->has($path));
    }

    public function testWrite()
    {
        $path = '/test/path/file.txt';
        $contents = 'content';

        $this->_filesystemMock->expects($this->once())
            ->method('write')
            ->with($path, $contents)
            ->willReturn(true);

        $this->assertEquals(true, $this->_object->write($path, $contents));
    }

    public function testWriteStream()
    {
        $path = '/test/path/file.txt';
        $resource = 'writestream';

        $this->_filesystemMock->expects($this->once())
            ->method('writeStream')
            ->with($path, $resource)
            ->willReturn(true);

        $this->assertEquals(true, $this->_object->writeStream($path, $resource));
    }

    public function testPut()
    {
        $path = '/test/path/file.txt';
        $contents = 'content';

        $this->_filesystemMock->expects($this->once())
            ->method('put')
            ->with($path, $contents)
            ->willReturn(true);

        $this->assertEquals(true, $this->_object->put($path, $contents));
    }

    public function testPutStream()
    {
        $path = '/test/path/file.txt';
        $stream = 'writeStream';

        $this->_filesystemMock->expects($this->once())
            ->method('putStream')
            ->with($path, $stream)
            ->willReturn(true);

        $this->assertEquals(true, $this->_object->putStream($path, $stream));
    }

    public function testReadAndDelete()
    {
        $path = '/test/path/file.txt';
        $content = 'content';

        $this->_filesystemMock->expects($this->once())
            ->method('readAndDelete')
            ->with($path)
            ->willReturn($content);

        $this->assertEquals($content, $this->_object->readAndDelete($path));
    }

    public function testUpdate()
    {
        $path = '/test/path/file.txt';
        $contents = 'content';

        $this->_filesystemMock->expects($this->once())
            ->method('update')
            ->with($path, $contents)
            ->willReturn(true);

        $this->assertEquals(true, $this->_object->update($path, $contents));
    }

    public function testUpdateStream()
    {
        $path = '/test/path/file.txt';
        $stream = 'writeStream';

        $this->_filesystemMock->expects($this->once())
            ->method('updateStream')
            ->with($path, $stream)
            ->willReturn(true);

        $this->assertEquals(true, $this->_object->updateStream($path, $stream));
    }

    public function testRead()
    {
        $path = '/test/path/file.txt';
        $contents = 'content';

        $this->_filesystemMock->expects($this->once())
            ->method('read')
            ->with($path)
            ->willReturn($contents);

        $this->assertEquals($contents, $this->_object->read($path));
    }

    public function testReadStream()
    {
        $path = '/test/path/file.txt';
        $stream = 'readStream';

        $this->_filesystemMock->expects($this->once())
            ->method('readStream')
            ->with($path)
            ->willReturn($stream);

        $this->assertEquals($stream, $this->_object->readStream($path));
    }

    public function testRename()
    {
        $path = '/test/path/file.txt';
        $newPath = '/test/path/new_file.txt';

        $this->_filesystemMock->expects($this->once())
            ->method('rename')
            ->with($path, $newPath)
            ->willReturn(true);

        $this->assertEquals(true, $this->_object->rename($path, $newPath));
    }

    public function testCopy()
    {
        $path = '/test/path/file.txt';
        $newPath = '/test/path/new_file.txt';

        $this->_filesystemMock->expects($this->once())
            ->method('copy')
            ->with($path, $newPath)
            ->willReturn(true);

        $this->assertEquals(true, $this->_object->copy($path, $newPath));
    }

    public function testDelete()
    {
        $path = '/test/path/file.txt';

        $this->_filesystemMock->expects($this->once())
            ->method('delete')
            ->with($path)
            ->willReturn(true);

        $this->assertEquals(true, $this->_object->delete($path));
    }

    public function testDeleteDir()
    {
        $path = '/test/path/';

        $this->_filesystemMock->expects($this->once())
            ->method('deleteDir')
            ->with($path)
            ->willReturn(true);

        $this->assertEquals(true, $this->_object->deleteDir($path));
    }

    public function testCreateDir()
    {
        $path = '/test/path/';

        $this->_filesystemMock->expects($this->once())
            ->method('createDir')
            ->with($path)
            ->willReturn(true);

        $this->assertEquals(true, $this->_object->createDir($path));
    }

    public function testListContents()
    {
        $path = '/test/path/';
        $contents = ['file1.jpg', 'file2.png'];

        $this->_filesystemMock->expects($this->once())
            ->method('listContents')
            ->with($path)
            ->willReturn($contents);

        $this->assertEquals($contents, $this->_object->listContents($path));
    }

    public function testGetMimetype()
    {
        $path = '/test/path/test.txt';
        $mimeType = 'mimetype/test';

        $this->_filesystemMock->expects($this->once())
            ->method('getMimetype')
            ->with($path)
            ->willReturn($mimeType);

        $this->assertEquals($mimeType, $this->_object->getMimetype($path));
    }

    public function testGetTimestamp()
    {
        $path = '/test/path/test.txt';
        $timestamp = 100000;

        $this->_filesystemMock->expects($this->once())
            ->method('getTimestamp')
            ->with($path)
            ->willReturn($timestamp);

        $this->assertEquals($timestamp, $this->_object->getTimestamp($path));
    }

    public function testGetVisibility()
    {
        $path = '/test/path/test.txt';
        $visibility = 'public';

        $this->_filesystemMock->expects($this->once())
            ->method('getVisibility')
            ->with($path)
            ->willReturn($visibility);

        $this->assertEquals($visibility, $this->_object->getVisibility($path));
    }

    public function testGetSize()
    {
        $path = '/test/path/test.txt';
        $size = 100;

        $this->_filesystemMock->expects($this->once())
            ->method('getSize')
            ->with($path)
            ->willReturn($size);

        $this->assertEquals($size, $this->_object->getSize($path));
    }

    public function testSetVisibility()
    {
        $path = '/test/path/test.txt';
        $visibility = 'public';

        $this->_filesystemMock->expects($this->once())
            ->method('setVisibility')
            ->with($path, $visibility)
            ->willReturn(true);

        $this->assertEquals(true, $this->_object->setVisibility($path, $visibility));
    }

    public function testGetMetadata()
    {
        $path = '/test/path/test.txt';
        $metadata = ['mime-type' => 'mimetype/test', 'user' => 'test'];

        $this->_filesystemMock->expects($this->once())
            ->method('getMetadata')
            ->with($path)
            ->willReturn($metadata);

        $this->assertEquals($metadata, $this->_object->getMetadata($path));
    }

    public function testAssertPresent()
    {
        $path = '/test/path/test.txt';

        $this->_filesystemMock->expects($this->once())
            ->method('assertPresent')
            ->with($path);

        $this->_object->assertPresent($path);
    }

    public function testAssertAbsent()
    {
        $path = '/test/path/test.txt';

        $this->_filesystemMock->expects($this->once())
            ->method('assertAbsent')
            ->with($path);

        $this->_object->assertAbsent($path);
    }
}