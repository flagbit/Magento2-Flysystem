<?php
namespace Flagbit\Flysystem\Test\Unit\Controller;

use \Flagbit\Flysystem\Adapter\FilesystemAdapter;
use \Flagbit\Flysystem\Controller\Adminhtml\Filesystem\OnInsert;
use \Flagbit\Flysystem\Helper\Filesystem;
use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Flagbit\Flysystem\Model\Filesystem\TmpManager;
use \Magento\Backend\App\Action\Context;
use \Magento\Backend\Model\Session;
use \Magento\Framework\App\Request\Http;
use \Magento\Framework\Controller\Result\Raw;
use \Magento\Framework\Controller\Result\RawFactory;
use \Magento\Framework\EntityManager\EventManager;
use \Magento\Framework\Logger\Monolog;
use \PHPUnit\Framework\MockObject\MockObject;
use \PHPUnit\Framework\TestCase;

class OnInsertTest extends TestCase
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
     * @var RawFactory|MockObject
     */
    protected $_resultRawFactoryMock;

    /**
     * @var Filesystem|MockObject
     */
    protected $_flysystemHelperMock;

    /**
     * @var TmpManager|MockObject
     */
    protected $_tmpManagerMock;

    /**
     * @var EventManager|MockObject
     */
    protected $_eventManagerMock;

    /**
     * @var Monolog|MockObject
     */
    protected $_loggerMock;

    /**
     * @var FilesystemAdapter|MockObject
     */
    protected $_flysystemAdapterMock;

    /**
     * @var Http|MockObject
     */
    protected $_httpMock;

    /**
     * @var Raw|MockObject
     */
    protected $_resultRawMock;

    /**
     * @var OnInsert
     */
    protected $_object;

    public function setUp(): void
    {
        $this->_contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->setMethods(['getRequest', 'getEventManager'])
            ->getMock();

        $this->_flysystemManagerMock = $this->getMockBuilder(Manager::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAdapter', 'getModalIdentifier'])
            ->getMock();

        $this->_sessionMock = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_resultRawFactoryMock = $this->getMockBuilder(RawFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->_flysystemHelperMock = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->setMethods(['idDecode'])
            ->getMock();

        $this->_tmpManagerMock = $this->getMockBuilder(TmpManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['writeTmp'])
            ->getMock();

        $this->_eventManagerMock = $this->getMockBuilder(EventManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['dispatch'])
            ->getMock();

        $this->_loggerMock = $this->getMockBuilder(Monolog::class)
            ->disableOriginalConstructor()
            ->setMethods(['critical'])
            ->getMock();

        $this->_flysystemAdapterMock = $this->getMockBuilder(FilesystemAdapter::class)
            ->disableOriginalConstructor()
            ->setMethods(['read'])
            ->getMock();

        $this->_httpMock = $this->getMockBuilder(Http::class)
            ->disableOriginalConstructor()
            ->setMethods(['getParam'])
            ->getMock();

        $this->_resultRawMock = $this->getMockBuilder(Raw::class)
            ->disableOriginalConstructor()
            ->setMethods(['setContents'])
            ->getMock();

        $this->_contextMock->expects($this->once())
            ->method('getRequest')
            ->willReturn($this->_httpMock);

        $this->_contextMock->expects($this->once())
            ->method('getEventManager')
            ->willReturn($this->_eventManagerMock);

        $this->_object = new OnInsert(
            $this->_contextMock,
            $this->_flysystemManagerMock,
            $this->_sessionMock,
            $this->_resultRawFactoryMock,
            $this->_flysystemHelperMock,
            $this->_tmpManagerMock,
            $this->_loggerMock
        );
    }

    public function testExecute(): void
    {
        $filename = 'TEST';
        $decodedfile = '/path/to/file/test.jpg';
        $modalId = 'test_modal';
        $contents = 'filecontent';
        $as_is = false;

        $this->_flysystemManagerMock->expects($this->atLeast(1))
            ->method('getAdapter')
            ->willReturn($this->_flysystemAdapterMock);

        $this->_httpMock->expects($this->at(0))
            ->method('getParam')
            ->with('filename')
            ->willReturn($filename);

        $this->_httpMock->expects($this->at(1))
            ->method('getParam')
            ->with('as_is')
            ->willReturn($as_is);

        $this->_flysystemHelperMock->expects($this->once())
            ->method('idDecode')
            ->with($filename)
            ->willReturn($decodedfile);

        $this->_flysystemAdapterMock->expects($this->once())
            ->method('read')
            ->with($decodedfile)
            ->willReturn($contents);

        $this->_tmpManagerMock->expects($this->once())
            ->method('writeTmp')
            ->with($decodedfile, $contents);

        $this->_flysystemManagerMock->expects($this->once())
            ->method('getModalIdentifier')
            ->willReturn($modalId);

        $this->_eventManagerMock->expects($this->once())
            ->method('dispatch')
            ->with($this->isType('string'), $this->isType('array'));

        $this->_resultRawFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->_resultRawMock);

        $this->_resultRawMock->expects($this->once())
            ->method('setContents')
            ->with($decodedfile)
            ->willReturn($this->_resultRawMock);

        $this->assertEquals($this->_resultRawMock, $this->_object->execute());
    }

    public function testExecuteException(): void
    {
        $exception = new \Exception('test');

         $this->_flysystemManagerMock->expects($this->once())
            ->method('getAdapter')
            ->willThrowException($exception);

         $this->_loggerMock->expects($this->once())
             ->method('critical')
             ->with($exception->getMessage());

         $this->_resultRawFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->_resultRawMock);

        $this->_resultRawMock->expects($this->once())
            ->method('setContents')
            ->with('')
            ->willReturn($this->_resultRawMock);

        $this->assertEquals($this->_resultRawMock, $this->_object->execute());
    }


}