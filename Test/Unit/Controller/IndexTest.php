<?php
namespace Flagbit\Flysystem\Test\Unit\Controller;

use \Flagbit\Flysystem\Adapter\FilesystemAdapter;
use \Flagbit\Flysystem\Controller\Adminhtml\Filesystem\Index;
use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Magento\Backend\App\Action\Context;
use \Magento\Backend\Model\Session;
use \Magento\Framework\App\Request\Http;
use \Magento\Framework\Controller\Result\Json;
use \Magento\Framework\Controller\Result\JsonFactory;
use \Magento\Framework\View\Result\Layout;
use \Magento\Framework\View\Result\LayoutFactory;
use \PHPUnit\Framework\MockObject\MockObject;
use \PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
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
     * @var LayoutFactory|MockObject
     */
    protected $_resultLayoutFactoryMock;

    /**
     * @var JsonFactory|MockObject
     */
    protected $_resultJsonFactoryMock;

    /**
     * @var FilesystemAdapter|MockObject
     */
    protected $_flysystemAdapterMock;

    /**
     * @var Layout|MockObject
     */
    protected $_resultLayoutMock;

    /**
     * @var Json|MockObject
     */
    protected $_resultJsonMock;

    /**
     * @var Http|MockObject
     */
    protected $_httpMock;

    /**
     * @var Index
     */
    protected $_object;


    public function setUp()
    {
        $this->_contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->setMethods(['getRequest'])
            ->getMock();

        $this->_flysystemManagerMock = $this->getMockBuilder(Manager::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAdapter', 'setModalIdentifier'])
            ->getMock();

        $this->_sessionMock = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_resultLayoutFactoryMock = $this->getMockBuilder(LayoutFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->_resultJsonFactoryMock = $this->getMockBuilder(JsonFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->_flysystemAdapterMock = $this->getMockBuilder(FilesystemAdapter::class)
            ->disableOriginalConstructor()
            ->setMethods()
            ->getMock();

        $this->_resultLayoutMock = $this->getMockBuilder(Layout::class)
            ->disableOriginalConstructor()
            ->setMethods(['addHandle'])
            ->getMock();

        $this->_resultJsonMock = $this->getMockBuilder(Json::class)
            ->disableOriginalConstructor()
            ->setMethods(['setData'])
            ->getMock();

        $this->_httpMock = $this->getMockBuilder(Http::class)
            ->disableOriginalConstructor()
            ->setMethods(['getParam'])
            ->getMock();

        $this->_contextMock->expects($this->once())
            ->method('getRequest')
            ->willReturn($this->_httpMock);

        $this->_object = new Index(
            $this->_contextMock,
            $this->_flysystemManagerMock,
            $this->_sessionMock,
            $this->_resultLayoutFactoryMock,
            $this->_resultJsonFactoryMock
        );
    }

    public function testExecute()
    {
        $identifier = 'test';

        $this->_flysystemManagerMock->expects($this->exactly(2))
            ->method('getAdapter')
            ->willReturn($this->_flysystemAdapterMock);

        $this->_httpMock->expects($this->once())
            ->method('getParam')
            ->with('identifier')
            ->willReturn($identifier);

        $this->_flysystemManagerMock->expects($this->once())
            ->method('setModalIdentifier')
            ->with($identifier);

        $this->_resultLayoutFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->_resultLayoutMock);

        $this->_resultLayoutMock->expects($this->once())
            ->method('addHandle')
            ->with('overlay_popup');

        $this->assertEquals($this->_resultLayoutMock, $this->_object->execute());
    }

    public function testExecuteException()
    {
        $exception = new \Exception();

        $this->_flysystemManagerMock->expects($this->once())
            ->method('getAdapter')
            ->willThrowException($exception);

        $this->_resultJsonFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->_resultJsonMock);

        $this->_resultJsonMock->expects($this->once())
            ->method('setData')
            ->with($this->arrayHasKey('message'))
            ->willReturn($this->_resultJsonMock);

        $this->assertEquals($this->_resultJsonMock, $this->_object->execute());
    }
}