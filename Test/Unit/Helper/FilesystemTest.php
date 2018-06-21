<?php
namespace Flagbit\Flysystem\Test\Unit\Helper;

use \Flagbit\Flysystem\Helper\Filesystem;
use \Magento\Framework\App\Helper\Context;
use \Magento\Framework\App\Request\Http;
use \PHPUnit\Framework\MockObject\MockObject;

class FilesystemTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Context|MockObject
     */
    protected $_contextMock;

    /**
     * @var Http|MockObject
     */
    protected $_requestMock;

    /**
     * @var Filesystem
     */
    protected $_object;

    public function setUp()
    {
        $this->_contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->setMethods(['getRequest'])
            ->getMock();

        $this->_requestMock = $this->getMockBuilder(Http::class)
            ->disableOriginalConstructor()
            ->setMethods(['getParam'])
            ->getMock();

        $this->_contextMock->expects($this->once())
            ->method('getRequest')
            ->willReturn($this->_requestMock);

        $this->_object = new Filesystem(
            $this->_contextMock
        );
    }

    public function testGetCurrentPath()
    {
        $requestPath = $this->_object->idEncode('/test/path');
        $expectedPath = '/test/path';

        $this->_requestMock->expects($this->once())
            ->method('getParam')
            ->with($this->_object->getTreeNodeName())
            ->willReturn($requestPath);

        $this->assertEquals($expectedPath, $this->_object->getCurrentPath());
    }

    public function testGetCurrentPathEmpty()
    {
        $path = '/';

        $this->_requestMock->expects($this->once())
            ->method('getParam')
            ->with($this->_object->getTreeNodeName())
            ->willReturn('');

        $this->assertEquals($path, $this->_object->getCurrentPath());
    }

    public function testGetShortFilenameShort()
    {
        $filename = 'test';

        $this->assertEquals($filename, $this->_object->getShortFilename($filename));
    }

    public function testGetShortFilenameLong()
    {
        $filename = 'test';
        $expected = 'tes...';

        $this->assertEquals($expected, $this->_object->getShortFilename($filename, 3));
    }

}