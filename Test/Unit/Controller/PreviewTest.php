<?php
namespace Flagbit\Flysystem\Test\Unit\Controller;

use \Flagbit\Flysystem\Adapter\FilesystemAdapter;
use \Flagbit\Flysystem\Controller\Adminhtml\Filesystem\Preview;
use \Flagbit\Flysystem\Helper\Filesystem;
use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Flagbit\Flysystem\Model\Filesystem\TmpManager;
use \Magento\Backend\App\Action\Context;
use \Magento\Backend\Model\Session;
use \Magento\Backend\Model\UrlInterface;
use \Magento\Framework\App\Request\Http;
use \Magento\Framework\Controller\Result\Json;
use \Magento\Framework\Controller\Result\JsonFactory;
use \Magento\Framework\Logger\Monolog;
use \Magento\Store\Model\Store;
use \Magento\Store\Model\StoreManager;
use \PHPUnit\Framework\MockObject\MockObject;
use \PHPUnit\Framework\TestCase;

class PreviewTest extends TestCase
{
    /**
     * @var Filesystem|MockObject
     */
    protected $_flysystemHelperMock;

    /**
     * @var TmpManager|MockObject
     */
    protected $_tmpManagerMock;

    /**
     * @var JsonFactory|MockObject
     */
    protected $_resultJsonFactoryMock;

    /**
     * @var Monolog|MockObject
     */
    protected $_loggerMock;

    /**
     * @var StoreManager|MockObject
     */
    protected $_storeManagerMock;

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
     * @var Json|MockObject
     */
    protected $_resultJsonMock;

    /**
     * @var Store|MockObject
     */
    protected $_storeMock;

    /**
     * @var FilesystemAdapter|MockObject
     */
    protected $_flysystemAdapterMock;

    /**
     * @var Http|MockObject
     */
    protected $_requestMock;

    /**
     * @var Preview
     */
    protected $_object;

    public function setUp()
    {
        $this->_flysystemHelperMock = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->setMethods(['idDecode'])
            ->getMock();

        $this->_tmpManagerMock = $this->getMockBuilder(TmpManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['writePreview', 'getUserPreviewDir'])
            ->getMock();

        $this->_resultJsonFactoryMock = $this->getMockBuilder(JsonFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->_loggerMock = $this->getMockBuilder(Monolog::class)
            ->disableOriginalConstructor()
            ->setMethods(['critical'])
            ->getMock();

        $this->_storeManagerMock = $this->getMockBuilder(StoreManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['getStore'])
            ->getMock();

        $this->_contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->setMethods(['getRequest'])
            ->getMock();

        $this->_flysystemManagerMock = $this->getMockBuilder(Manager::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAdapter'])
            ->getMock();

        $this->_sessionMock = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_resultJsonMock = $this->getMockBuilder(Json::class)
            ->disableOriginalConstructor()
            ->setMethods(['setData'])
            ->getMock();

        $this->_storeMock = $this->getMockBuilder(Store::class)
            ->disableOriginalConstructor()
            ->setMethods(['getBaseUrl'])
            ->getMock();

        $this->_flysystemAdapterMock = $this->getMockBuilder(FilesystemAdapter::class)
            ->disableOriginalConstructor()
            ->setMethods(['read'])
            ->getMock();

        $this->_requestMock = $this->getMockBuilder(Http::class)
            ->disableOriginalConstructor()
            ->setMethods(['getParam'])
            ->getMock();

        $this->_contextMock->expects($this->once())
            ->method('getRequest')
            ->willReturn($this->_requestMock);

        $this->_object = new Preview(
            $this->_contextMock,
            $this->_flysystemManagerMock,
            $this->_sessionMock,
            $this->_flysystemHelperMock,
            $this->_tmpManagerMock,
            $this->_resultJsonFactoryMock,
            $this->_loggerMock,
            $this->_storeManagerMock
        );
    }

    public function testExecute()
    {
        $encodedFile = 'TESTTEST';
        $decodedFile = 'path/test.jpg';
        $contents = 'test';

        $previewDir = 'flagbit_flysystem/.preview/user';
        $mediaUrl = 'test.test/media';

        $this->_flysystemManagerMock->expects($this->atLeast(1))
            ->method('getAdapter')
            ->willReturn($this->_flysystemAdapterMock);

        $this->_requestMock->expects($this->once())
            ->method('getParam')
            ->with('filename')
            ->willReturn($encodedFile);

        $this->_flysystemHelperMock->expects($this->once())
            ->method('idDecode')
            ->with($encodedFile)
            ->willReturn($decodedFile);

        $this->_flysystemAdapterMock->expects($this->once())
            ->method('read')
            ->with($decodedFile)
            ->willReturn($contents);

        $this->_tmpManagerMock->expects($this->once())
            ->method('writePreview')
            ->with($decodedFile, $contents)
            ->willReturn(true);

        $this->_tmpManagerMock->expects($this->once())
            ->method('getUserPreviewDir')
            ->willReturn($previewDir);

        $this->_storeManagerMock->expects($this->once())
            ->method('getStore')
            ->willReturn($this->_storeMock);

        $this->_storeMock->expects($this->once())
            ->method('getBaseUrl')
            ->with(UrlInterface::URL_TYPE_MEDIA)
            ->willReturn($mediaUrl);

        $this->_resultJsonFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->_resultJsonMock);

        $this->_resultJsonMock->expects($this->once())
            ->method('setData')
            ->with($this->arrayHasKey('url'))
            ->willReturn($this->_resultJsonMock);

        $this->assertEquals($this->_resultJsonMock, $this->_object->execute());
    }

    public function testExecuteException()
    {
        $exception = new \Exception('test');

        $this->_flysystemManagerMock->expects($this->once())
            ->method('getAdapter')
            ->willThrowException($exception);

        $this->_loggerMock->expects($this->once())
            ->method('critical')
            ->with($exception->getMessage());

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