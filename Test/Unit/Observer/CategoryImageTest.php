<?php
namespace Flagbit\Flysystem\Test\Unit\Observer;

use \Flagbit\Flysystem\Adapter\FilesystemAdapter;
use \Flagbit\Flysystem\Controller\Adminhtml\Filesystem\OnInsert;
use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Flagbit\Flysystem\Model\Filesystem\TmpManager;
use \Flagbit\Flysystem\Observer\CategoryImage;
use \Magento\Framework\DataObject;
use \Magento\Framework\Event\Observer;
use \Magento\Framework\Logger\Monolog;
use \PHPUnit\Framework\MockObject\MockObject;
use \PHPUnit\Framework\TestCase;

class CategoryImageTest extends TestCase
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
     * @var Manager|MockObject
     */
    protected $_flysystemManagerMock;

    /**
     * @var FilesystemAdapter|MockObject
     */
    protected $_flysystemAdapterMock;

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
     * @var CategoryImage
     */
    protected $_object;


    public function setUp()
    {
        $this->_tmpManagerMock = $this->getMockBuilder(TmpManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['createCategoryTmp', 'getAbsoluteTmpPath'])
            ->getMock();

        $this->_loggerMock = $this->getMockBuilder(Monolog::class)
            ->disableOriginalConstructor()
            ->setMethods(['critical'])
            ->getMock();

        $this->_flysystemManagerMock = $this->getMockBuilder(Manager::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAdapter'])
            ->getMock();

        $this->_flysystemAdapterMock = $this->getMockBuilder(FilesystemAdapter::class)
            ->disableOriginalConstructor()
            ->setMethods(['getMimetype', 'getSize'])
            ->getMock();

        $this->_observerMock = $this->getMockBuilder(Observer::class)
            ->disableOriginalConstructor()
            ->setMethods(['getEvent'])
            ->getMock();

        $this->_dataObjectMock = $this->getMockBuilder(DataObject::class)
            ->disableOriginalConstructor()
            ->setMethods(['getData'])
            ->getMock();

        $this->_onInsertMock = $this->getMockBuilder(OnInsert::class)
            ->disableOriginalConstructor()
            ->setMethods(['setResult'])
            ->getMock();

        $this->_object = new CategoryImage(
            $this->_tmpManagerMock,
            $this->_loggerMock
        );
    }

    public function testExecute()
    {
        $modalId = 'category_modal';
        $filename = 'test.jpg';

        $file = [
            'name' => basename($filename),
            'type' => 'mimetype/test',
            'tmp_name' => 'test/path/'.$filename,
            'error' => 0,
            'size' => 100
        ];

        $result = [
            'name' => basename($filename),
            'url' => 'test.test/'.$filename
        ];

        $this->_observerMock->expects($this->exactly(4))
            ->method('getEvent')
            ->willReturn($this->_dataObjectMock);

        $this->_dataObjectMock->expects($this->at(0))
            ->method('getData')
            ->with('modal_id')
            ->willReturn($modalId);

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

        $this->_flysystemManagerMock->expects($this->exactly(2))
            ->method('getAdapter')
            ->willReturn($this->_flysystemAdapterMock);

        $this->_flysystemAdapterMock->expects($this->once())
            ->method('getMimetype')
            ->with($filename)
            ->willReturn($file['type']);

        $this->_tmpManagerMock->expects($this->once())
            ->method('getAbsoluteTmpPath')
            ->with($filename)
            ->willReturn($file['tmp_name']);

        $this->_flysystemAdapterMock->expects($this->once())
            ->method('getSize')
            ->with($filename)
            ->willReturn($file['size']);

        $this->_tmpManagerMock->expects($this->once())
            ->method('createCategoryTmp')
            ->with($file)
            ->willReturn($result);

        $this->_onInsertMock->expects($this->once())
            ->method('setResult')
            ->with(json_encode($result));

        $this->_object->execute($this->_observerMock);
    }

    public function testExecuteWrongModalId()
    {
        $modalId = 'invalid';

        $this->_observerMock->expects($this->once())
            ->method('getEvent')
            ->willReturn($this->_dataObjectMock);

        $this->_dataObjectMock->expects($this->once())
            ->method('getData')
            ->with('modal_id')
            ->willReturn($modalId);

        $this->_object->execute($this->_observerMock);
    }

    public function testExecuteException()
    {
        $modalId = 'category_modal';
        $filename = 'test.jpg';

        $this->_observerMock->expects($this->exactly(4))
            ->method('getEvent')
            ->willReturn($this->_dataObjectMock);

        $this->_dataObjectMock->expects($this->at(0))
            ->method('getData')
            ->with('modal_id')
            ->willReturn($modalId);

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
            ->willReturn('invalid');

        $this->_loggerMock->expects($this->once())
            ->method('critical')
            ->with($this->isType('string'));

        $this->_object->execute($this->_observerMock);
    }
}