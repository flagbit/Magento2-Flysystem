<?php
namespace Flagbit\Flsystem\Test\Unit\Block\Adminhtml\Filesystem;

use \Flagbit\Flysystem\Adapter\FilesystemAdapter;
use \Flagbit\Flysystem\Block\Adminhtml\Filesystem\Tree;
use \Flagbit\Flysystem\Helper\Filesystem;
use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Magento\Backend\Block\Template\Context;
use \Magento\Backend\Model\Session;
use \Magento\Backend\Model\Url;
use \Magento\Framework\Logger\Monolog;
use \Magento\Framework\Serialize\Serializer\Json;
use \PHPUnit\Framework\MockObject\MockObject;

class TreeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Tree
     */
    protected $_object;

    /**
     * @var Context|MockObject
     */
    protected $_contextMock;

    /**
     * @var Manager|MockObject
     */
    protected $_flysystemManagerMock;

    /**
     * @var Filesystem|MockObject
     */
    protected $_flysystemHelperMock;

    /**
     * @var Json|MockObject
     */
    protected $_serializerMock;

    /**
     * @var Monolog|MockObject
     */
    protected $_loggerMock;

    /**
     * @var Url|MockObject
     */
    protected $_urlBuilderMock;

    /**
     * @var FilesystemAdapter|MockObject
     */
    protected $_flysystemAdapterMock;

    /**
     * @var Session|MockObject
     */
    protected $_sessionMock;

    protected function setUp(): void
    {
        $this->_contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->setMethods(['getLogger', 'getUrlBuilder'])
            ->getMock();

        $this->_flysystemManagerMock = $this->getMockBuilder(Manager::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAdapter', 'getSession'])
            ->getMock();

        $this->_flysystemHelperMock = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCurrentPath', 'getShortFilename', 'idEncode'])
            ->getMock();

        $this->_serializerMock = $this->getMockBuilder(Json::class)
            ->disableOriginalConstructor()
            ->setMethods(['serialize'])
            ->getMock();

        $this->_loggerMock = $this->getMockBuilder(Monolog::class)
            ->disableOriginalConstructor()
            ->setMethods(['error'])
            ->getMock();

        $this->_flysystemAdapterMock = $this->getMockBuilder(FilesystemAdapter::class)
            ->disableOriginalConstructor()
            ->setMethods(['listContents'])
            ->getMock();

        $this->_urlBuilderMock = $this->getMockBuilder(Url::class)
            ->disableOriginalConstructor()
            ->setMethods(['getUrl'])
            ->getMock();

        $this->_sessionMock = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCurrentPath'])
            ->getMock();

        $this->_contextMock->expects($this->once())
            ->method('getLogger')
            ->willReturn($this->_loggerMock);

        $this->_contextMock->expects($this->once())
            ->method('getUrlBuilder')
            ->willReturn($this->_urlBuilderMock);

        $this->_object = new Tree(
            $this->_contextMock,
            $this->_flysystemManagerMock,
            $this->_flysystemHelperMock,
            $this->_serializerMock
        );
    }

    public function testGetTreeJson(): void
    {
        $path = '/';

        $filesArray = [
            0 => [
                'type' => 'file',
                'path' => 'test1.jpg',
                'timestamp' => 0,
                'size' => 20,
                'dirname' => '',
                'basename' => 'test1.jpg',
                'extension' => 'jpg',
                'filename' => 'test1'
            ],
            1 => [
                'type' => 'dir',
                'path' => 'test',
                'timestamp' => 0,
                'dirname' => '',
                'basename' => 'test',
                'filename' => 'test'
            ]
        ];

        $expectedJsonArray = [
            0 => [
                'text' => 'shortFilename',
                'id' => 'encodedFileId',
                'path' => '/test',
                'cls' => 'folder'
            ]
        ];

        $this->_flysystemHelperMock->expects($this->once())
            ->method('getCurrentPath')
            ->willReturn($path);

        $this->_flysystemManagerMock->expects($this->once())
            ->method('getAdapter')
            ->willReturn($this->_flysystemAdapterMock);

        $this->_flysystemAdapterMock->expects($this->once())
            ->method('listContents')
            ->with($path)
            ->willReturn($filesArray);

        $this->_flysystemHelperMock->expects($this->once())
            ->method('getShortFilename')
            ->with($filesArray[1]['path'])
            ->willReturn('shortFilename');

        $this->_flysystemHelperMock->expects($this->once())
            ->method('idEncode')
            ->with('/'.$filesArray[1]['path'])
            ->willReturn('encodedFileId');

        $this->_serializerMock->expects($this->once())
            ->method('serialize')
            ->with($expectedJsonArray)
            ->willReturn('jsonString');

        $this->assertEquals('jsonString', $this->_object->getTreeJson());
    }

    public function testGetTreeJsonException(): void
    {
        $path = 'invalid';

        $exception = new \Exception();

        $this->_flysystemHelperMock->expects($this->once())
            ->method('getCurrentPath')
            ->willReturn($path);

        $this->_flysystemManagerMock->expects($this->once())
            ->method('getAdapter')
            ->willReturn($this->_flysystemAdapterMock);

        $this->_flysystemAdapterMock->expects($this->once())
            ->method('listContents')
            ->with($path)
            ->willThrowException($exception);

        $this->_loggerMock->expects($this->once())
            ->method('error')
            ->with($exception->getMessage());

        $this->_serializerMock->expects($this->once())
            ->method('serialize')
            ->with([])
            ->willReturn('emptyJson');

        $this->assertEquals('emptyJson', $this->_object->getTreeJson());
    }

    public function testGetTreeWidgetOptions(): void
    {
        $loaderUrl = 'test.url/test';
        $loaderRoute = 'flagbit_flysystem/*/treeJson';
        $currentPath = '/test/path/';

        $this->_urlBuilderMock->expects($this->once())
            ->method('getUrl')
            ->with($loaderRoute)
            ->willReturn($loaderUrl);

        $this->_flysystemManagerMock->expects($this->once())
            ->method('getSession')
            ->willReturn($this->_sessionMock);

        $this->_sessionMock->expects($this->once())
            ->method('getCurrentPath')
            ->willReturn($currentPath);

        $this->_flysystemHelperMock->expects($this->at(0))
            ->method('idEncode')
            ->with('test')
            ->willReturn('encoded1');

        $this->_flysystemHelperMock->expects($this->at(1))
            ->method('idEncode')
            ->with('test/path')
            ->willReturn('encoded2');

        $this->_serializerMock->expects($this->once())
            ->method('serialize')
            ->with($this->arrayHasKey('folderTree'))
            ->willReturn('serializedTree');

        $this->assertEquals('serializedTree', $this->_object->getTreeWidgetOptions());
    }
}