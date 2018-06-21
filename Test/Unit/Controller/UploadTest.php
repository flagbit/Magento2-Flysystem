<?php
namespace Flagbit\Flysystem\Test\Unit\Controller;

use \Flagbit\Flysystem\Adapter\FilesystemAdapter;
use \Flagbit\Flysystem\Controller\Adminhtml\Filesystem\Upload;
use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Flagbit\Flysystem\Model\Filesystem\UploadManager;
use \Magento\Backend\App\Action\Context;
use \Magento\Backend\Model\Session;
use \Magento\Framework\Controller\Result\Json;
use \Magento\Framework\Controller\Result\JsonFactory;
use \PHPUnit\Framework\MockObject\MockObject;
use \PHPUnit\Framework\TestCase;

class UploadTest extends TestCase
{
    /**
     * @var Context|MockObject
     */
    protected $_contextMock;

    /**
     * @var Manager|MockObject
     */
    protected $_flysystemManagerMock;

    /**
     * @var Session|MockObject
     */
    protected $_sessionMock;

    /**
     * @var JsonFactory|MockObject
     */
    protected $_resultJsonFactoryMock;

    /**
     * @var UploadManager|MockObject
     */
    protected $_uploadManagerMock;

    /**
     * @var FilesystemAdapter|MockObject
     */
    protected $_flysystemAdapterMock;

    /**
     * @var Json|MockObject
     */
    protected $_resultJsonMock;

    /**
     * @var Upload
     */
    protected $_object;

    public function setUp()
    {
        $this->_contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_flysystemManagerMock = $this->getMockBuilder(Manager::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAdapter', 'getSession'])
            ->getMock();

        $this->_sessionMock = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCurrentPath'])
            ->getMock();

        $this->_resultJsonFactoryMock = $this->getMockBuilder(JsonFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->_uploadManagerMock = $this->getMockBuilder(UploadManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['upload'])
            ->getMock();

        $this->_flysystemAdapterMock = $this->getMockBuilder(FilesystemAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_resultJsonMock = $this->getMockBuilder(Json::class)
            ->disableOriginalConstructor()
            ->setMethods(['setData'])
            ->getMock();

        $this->_object = new Upload(
            $this->_contextMock,
            $this->_flysystemManagerMock,
            $this->_sessionMock,
            $this->_resultJsonFactoryMock,
            $this->_uploadManagerMock
        );
    }

    public function testExecute()
    {
        $currentPath = '/test/path';

        $this->_flysystemManagerMock->expects($this->atLeast(1))
            ->method('getAdapter')
            ->willReturn($this->_flysystemAdapterMock);

        $this->_flysystemManagerMock->expects($this->once())
            ->method('getSession')
            ->willReturn($this->_sessionMock);

        $this->_sessionMock->expects($this->once())
            ->method('getCurrentPath')
            ->willReturn($currentPath);

        $this->_uploadManagerMock->expects($this->once())
            ->method('upload')
            ->with($this->_flysystemAdapterMock, $currentPath);

        $this->_resultJsonFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->_resultJsonMock);

        $this->_resultJsonMock->expects($this->once())
            ->method('setData')
            ->with($this->arrayHasKey('error'))
            ->willReturn($this->_resultJsonMock);

        $this->assertEquals($this->_resultJsonMock, $this->_object->execute());
    }

    public function testExecuteException()
    {
        $exception = new \Exception();

        $this->_flysystemManagerMock->expects($this->atLeast(1))
            ->method('getAdapter')
            ->willThrowException($exception);

        $this->_resultJsonFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->_resultJsonMock);

        $this->_resultJsonMock->expects($this->once())
            ->method('setData')
            ->with($this->arrayHasKey('errorcode'))
            ->willReturn($this->_resultJsonMock);

        $this->assertEquals($this->_resultJsonMock, $this->_object->execute());
    }
}