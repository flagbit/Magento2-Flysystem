<?php
namespace Flagbit\Flysystem\Test\Unit\Observer;

use \Flagbit\Flysystem\Adapter\FilesystemAdapter;
use \Flagbit\Flysystem\Controller\Adminhtml\Filesystem\OnInsert;
use \Flagbit\Flysystem\Helper\Filesystem;
use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Flagbit\Flysystem\Model\Filesystem\TmpManager;
use \Flagbit\Flysystem\Observer\CmsImage;
use \Magento\Framework\DataObject;
use \Magento\Framework\Event\Observer;
use \Magento\Framework\Filesystem\Directory\Write;
use \Magento\Framework\Logger\Monolog;
use \PHPUnit\Framework\MockObject\MockObject;
use \PHPUnit\Framework\TestCase;

class CmsImageTest extends TestCase
{
    /**
     * @var TmpManager|MockObject
     */
    protected $_tmpManagerMock;

    /**
     * @var Monolog|MockObject
     */
    protected $_loggerMock;

    /**
     * @var Filesystem|MockObject
     */
    protected $_flysystemHelperMock;

    /**
     * @var Write|MockObject
     */
    protected $_directoryListMock;

    /**
     * @var Observer|MockObject
     */
    protected $_observerMock;

    /**
     * @var DataObject|MockObject
     */
    protected $_dataObjectMock;

    /**
     * @var OnInsert|MockObject
     */
    protected $_onInsertMock;

    /**
     * @var Manager|MockObject
     */
    protected $_flysystemManagerMock;

    /**
     * @var FilesystemAdapter|MockObject
     */
    protected $_flysystemAdapterMock;

    /**
     * @var CmsImage
     */
    protected $_object;

    public function setUp()
    {
        $this->_tmpManagerMock = $this->getMockBuilder(TmpManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['getDirectoryListMedia', 'writeWysiwygFile'])
            ->getMock();

        $this->_loggerMock = $this->getMockBuilder(Monolog::class)
            ->disableOriginalConstructor()
            ->setMethods(['critical'])
            ->getMock();

        $this->_flysystemHelperMock = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->setMethods(['getImageHtmlDeclaration'])
            ->getMock();

        $this->_observerMock = $this->getMockBuilder(Observer::class)
            ->disableOriginalConstructor()
            ->setMethods(['getEvent', 'getEventName'])
            ->getMock();

        $this->_dataObjectMock = $this->getMockBuilder(DataObject::class)
            ->disableOriginalConstructor()
            ->setMethods(['getData'])
            ->getMock();

        $this->_onInsertMock = $this->getMockBuilder(OnInsert::class)
            ->disableOriginalConstructor()
            ->setMethods(['setResult'])
            ->getMock();

        $this->_flysystemManagerMock = $this->getMockBuilder(Manager::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAdapter', 'getPath'])
            ->getMock();

        $this->_flysystemAdapterMock = $this->getMockBuilder(FilesystemAdapter::class)
            ->disableOriginalConstructor()
            ->setMethods(['read'])
            ->getMock();

        $this->_directoryListMock = $this->getMockBuilder(Write::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAbsolutePath'])
            ->getMock();

        $this->_object = new CmsImage(
            $this->_tmpManagerMock,
            $this->_loggerMock,
            $this->_flysystemHelperMock
        );
    }

    private function _expectObserverInitializeSuccess($filename)
    {
        $this->_observerMock->expects($this->exactly(5))
            ->method('getEvent')
            ->willReturn($this->_dataObjectMock);

        $this->_dataObjectMock->expects($this->at(0))
            ->method('getData')
            ->with('modal_id')
            ->willReturn('flagbit_cms_modal');

        $this->_dataObjectMock->expects($this->at(1))
            ->method('getData')
            ->with('manager')
            ->willReturn($this->_flysystemManagerMock);

        $this->_dataObjectMock->expects($this->at(2))
            ->method('getData')
            ->with('filename')
            ->willReturn($filename);

        $this->_dataObjectMock->expects($this->at(3))
            ->method('getData')
            ->with('controller')
            ->willReturn($this->_onInsertMock);

        $this->_dataObjectMock->expects($this->at(4))
            ->method('getData')
            ->with('as_is')
            ->willReturn(true);
    }

    public function testExecute()
    {
        $filename = 'test/test.jpg';
        $content = 'test';

        $managerPath = '/magento/test/';
        $mediaPath = '/magento/pub/media/';

        $newFile = 'wysiwyg/test.jpg';
        $imageHtml = 'imageHtml';

        $this->_expectObserverInitializeSuccess($filename);

        $this->_flysystemManagerMock->expects($this->once())
            ->method('getAdapter')
            ->willReturn($this->_flysystemAdapterMock);

        $this->_flysystemAdapterMock->expects($this->once())
            ->method('read')
            ->with($filename)
            ->willReturn($content);

        $this->_flysystemManagerMock->expects($this->once())
            ->method('getPath')
            ->willReturn($managerPath);

        $this->_tmpManagerMock->expects($this->once())
            ->method('getDirectoryListMedia')
            ->willReturn($this->_directoryListMock);

        $this->_directoryListMock->expects($this->once())
            ->method('getAbsolutePath')
            ->willReturn($mediaPath);

        $this->_tmpManagerMock->expects($this->once())
            ->method('writeWysiwygFile')
            ->with('test.jpg', $content)
            ->willReturn($newFile);

        $this->_flysystemHelperMock->expects($this->once())
            ->method('getImageHtmlDeclaration')
            ->with($newFile, true)
            ->willReturn($imageHtml);

        $this->_onInsertMock->expects($this->once())
            ->method('setResult')
            ->with($imageHtml);

        $this->_object->execute($this->_observerMock);
    }

    public function testExecuteMediaFile()
    {
        $filename = 'media/wysiwyg/test.jpg';
        $content = 'test';

        $managerPath = '/magento/pub/';
        $mediaPath = '/magento/pub/media/';

        $newFile = 'wysiwyg/test.jpg';
        $imageHtml = 'imageHtml';

        $this->_expectObserverInitializeSuccess($filename);

        $this->_flysystemManagerMock->expects($this->once())
            ->method('getAdapter')
            ->willReturn($this->_flysystemAdapterMock);

        $this->_flysystemAdapterMock->expects($this->once())
            ->method('read')
            ->with($filename)
            ->willReturn($content);

        $this->_flysystemManagerMock->expects($this->once())
            ->method('getPath')
            ->willReturn($managerPath);

        $this->_tmpManagerMock->expects($this->once())
            ->method('getDirectoryListMedia')
            ->willReturn($this->_directoryListMock);

        $this->_directoryListMock->expects($this->once())
            ->method('getAbsolutePath')
            ->willReturn($mediaPath);

        $this->_tmpManagerMock->expects($this->never())
            ->method('writeWysiwygFile');

        $this->_flysystemHelperMock->expects($this->once())
            ->method('getImageHtmlDeclaration')
            ->with($newFile, true)
            ->willReturn($imageHtml);

        $this->_onInsertMock->expects($this->once())
            ->method('setResult')
            ->with($imageHtml);

        $this->_object->execute($this->_observerMock);
    }

    public function testExecuteInvalidObserverParameters()
    {
        $invalidManager = null;
        $invalidController = null;
        $eventName = 'test';

        $this->_observerMock->expects($this->exactly(5))
            ->method('getEvent')
            ->willReturn($this->_dataObjectMock);

        $this->_dataObjectMock->expects($this->at(0))
            ->method('getData')
            ->with('modal_id')
            ->willReturn('flagbit_cms_modal');

        $this->_dataObjectMock->expects($this->at(1))
            ->method('getData')
            ->with('manager')
            ->willReturn($invalidManager);

        $this->_dataObjectMock->expects($this->at(2))
            ->method('getData')
            ->with('filename')
            ->willReturn('test.jpg');

        $this->_dataObjectMock->expects($this->at(3))
            ->method('getData')
            ->with('controller')
            ->willReturn($invalidController);

        $this->_dataObjectMock->expects($this->at(4))
            ->method('getData')
            ->with('as_is')
            ->willReturn(true);

        $this->_observerMock->expects($this->once())
            ->method('getEventName')
            ->willReturn($eventName);

        $this->_loggerMock->expects($this->once())
            ->method('critical')
            ->with($this->isType('string'));

        $this->_object->execute($this->_observerMock);
    }
}