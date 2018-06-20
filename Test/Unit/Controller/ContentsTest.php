<?php
namespace Flagbit\Flysystem\Test\Unit\Controller;

use \Flagbit\Flysystem\Adapter\FilesystemAdapter;
use \Flagbit\Flysystem\Controller\Adminhtml\Filesystem\Contents;
use \Flagbit\Flysystem\Helper\Filesystem;
use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Magento\Backend\Model\Session;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\App\ObjectManager;
use \Magento\Framework\Controller\Result\Json;
use \Magento\Framework\Controller\Result\JsonFactory;
use \Magento\Framework\View\Result\Layout;
use \Magento\Framework\View\Result\LayoutFactory;
use \PHPUnit\Framework\MockObject\MockObject;
use \PHPUnit\Framework\TestCase;

class ContentsTest extends TestCase
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
     * @var ObjectManager|MockObject
     */
    protected $_objectManagerMock;

    /**
     * @var Filesystem|MockObject
     */
    protected $_flysystemHelperMock;

    /**
     * @var Layout|MockObject
     */
    protected $_resultLayoutMock;

    /**
     * @var Json|MockObject
     */
    protected $_resultJsonMock;

    /**
     * @var Contents
     */
    protected $_object;


    public function setUp()
    {
        $this->_contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->setMethods(['getObjectManager'])
            ->getMock();

        $this->_flysystemManagerMock = $this->getMockBuilder(Manager::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAdapter', 'getSession'])
            ->getMock();

        $this->_sessionMock = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->setMethods(['setCurrentPath'])
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

        $this->_objectManagerMock = $this->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();

        $this->_flysystemHelperMock = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCurrentPath'])
            ->getMock();

        $this->_resultLayoutMock = $this->getMockBuilder(Layout::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_resultJsonMock = $this->getMockBuilder(Json::class)
            ->disableOriginalConstructor()
            ->setMethods(['setData'])
            ->getMock();

        $this->_contextMock->expects($this->once())
            ->method('getObjectManager')
            ->willReturn($this->_objectManagerMock);

        $this->_object = new Contents(
            $this->_contextMock,
            $this->_flysystemManagerMock,
            $this->_sessionMock,
            $this->_resultLayoutFactoryMock,
            $this->_resultJsonFactoryMock
        );
    }


    public function testExecute()
    {
        $currentPath = 'test/path/';

        $this->_flysystemManagerMock->expects($this->atMost(2))
            ->method('getAdapter')
            ->willReturn($this->_flysystemAdapterMock);

        $this->_objectManagerMock->expects($this->once())
            ->method('get')
            ->with(Filesystem::class)
            ->willReturn($this->_flysystemHelperMock);

        $this->_flysystemHelperMock->expects($this->once())
            ->method('getCurrentPath')
            ->willReturn($currentPath);

        $this->_flysystemManagerMock->expects($this->once())
            ->method('getSession')
            ->willReturn($this->_sessionMock);

        $this->_sessionMock->expects($this->once())
            ->method('setCurrentPath')
            ->with($currentPath);

        $this->_resultLayoutFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->_resultLayoutMock);

        $this->assertEquals($this->_resultLayoutMock, $this->_object->execute());
    }

    public function testExecuteException()
    {
        $exception = new \Exception();

        $this->_flysystemManagerMock->expects($this->atMost(2))
            ->method('getAdapter')
            ->willThrowException($exception);

        $this->_resultJsonFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->_resultJsonMock);

        $this->_resultJsonMock->expects($this->once())
            ->method('setData')
            ->with($this->arrayHasKey('message'));

        $this->assertEquals($this->_resultJsonMock, $this->_object->execute());
    }
}