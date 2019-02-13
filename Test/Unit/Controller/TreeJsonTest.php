<?php
namespace Flagbit\Flysystem\Tesŧ\Unit\Controller;

use \Flagbit\Flysystem\Adapter\FilesystemAdapter;
use \Flagbit\Flysystem\Block\Adminhtml\Filesystem\Tree;
use \Flagbit\Flysystem\Controller\Adminhtml\Filesystem\TreeJson;
use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Magento\Backend\App\Action\Context;
use \Magento\Backend\Model\Session;
use \Magento\Framework\Controller\Result\Json;
use \Magento\Framework\Controller\Result\JsonFactory;
use \Magento\Framework\View\Layout;
use \Magento\Framework\View\LayoutFactory;
use \PHPUnit\Framework\MockObject\MockObject;
use \PHPUnit\Framework\TestCase;

class TreeJsonTest extends TestCase
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
     * @var LayoutFactory|MockObject
     */
    protected $_layoutFactoryMock;

    /**
     * @var FilesystemAdapter|MockObject
     */
    protected $_flysystemAdapterMock;

    /**
     * @var Json|MockObject
     */
    protected $_resultJsonMock;

    /**
     * @var Layout|MockObject
     */
    protected $_layoutMock;

    /**
     * @var Tree|MockObject
     */
    protected $_treeMock;

    /**
     * @var TreeJson
     */
    protected $_object;

    protected function setUp(): void
    {
        $this->_contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_flysystemManagerMock = $this->getMockBuilder(Manager::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAdapter'])
            ->getMock();

        $this->_sessionMock = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_resultJsonFactoryMock = $this->getMockBuilder(JsonFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->_layoutFactoryMock = $this->getMockBuilder(LayoutFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->_flysystemAdapterMock = $this->getMockBuilder(FilesystemAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_resultJsonMock = $this->getMockBuilder(Json::class)
            ->disableOriginalConstructor()
            ->setMethods(['setData', 'setJsonData'])
            ->getMock();

        $this->_layoutMock = $this->getMockBuilder(Layout::class)
            ->disableOriginalConstructor()
            ->setMethods(['createBlock'])
            ->getMock();

        $this->_treeMock = $this->getMockBuilder(Tree::class)
            ->disableOriginalConstructor()
            ->setMethods(['getTreeJson'])
            ->getMock();

        $this->_object = new TreeJson(
            $this->_contextMock,
            $this->_flysystemManagerMock,
            $this->_sessionMock,
            $this->_resultJsonFactoryMock,
            $this->_layoutFactoryMock
        );
    }

    public function testExecute(): void
    {
        $treeJson = 'tree';

        $this->_resultJsonFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->_resultJsonMock);

        $this->_flysystemManagerMock->expects($this->once())
            ->method('getAdapter')
            ->willReturn($this->_flysystemAdapterMock);

        $this->_layoutFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->_layoutMock);

        $this->_layoutMock->expects($this->once())
            ->method('createBlock')
            ->with(Tree::class)
            ->willReturn($this->_treeMock);

        $this->_treeMock->expects($this->once())
            ->method('getTreeJson')
            ->willReturn($treeJson);

        $this->_resultJsonMock->expects($this->once())
            ->method('setJsonData')
            ->with($treeJson)
            ->willReturn($this->_resultJsonMock);

        $this->assertEquals($this->_resultJsonMock, $this->_object->execute());
    }

    public function testExecuteException(): void
    {
        $exception = new \Exception();

        $this->_resultJsonFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->_resultJsonMock);

        $this->_flysystemManagerMock->expects($this->once())
            ->method('getAdapter')
            ->willThrowException($exception);

        $this->_resultJsonMock->expects($this->once())
            ->method('setData')
            ->with($this->arrayHasKey('message'))
            ->willReturn($this->_resultJsonMock);

        $this->assertEquals($this->_resultJsonMock, $this->_object->execute());
    }
}