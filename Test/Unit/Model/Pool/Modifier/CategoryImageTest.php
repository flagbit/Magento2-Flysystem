<?php
namespace Flagbit\Flysystem\Test\Unit\Model\Pool\Modifier;

use \Flagbit\Flysystem\Adapter\FilesystemAdapter;
use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Flagbit\Flysystem\Model\Filesystem\TmpManager;
use \Flagbit\Flysystem\Model\Pool\Modifier\CategoryImage;
use \Magento\Framework\Exception\LocalizedException;
use \Monolog\Logger;
use \PHPUnit\Framework\MockObject\MockObject;

/**
 * Class CategoryImageTest
 * @package Flagbit\Flysystem\Test\Unit\Model\Pool\Modifier
 */
class CategoryImageTest extends \PhpUnit\Framework\TestCase
{
    /**
     * @var TmpManager|MockObject
     */
    protected $_tmpManagerMock;

    /**
     * @var Manager|MockObject
     */
    protected $_managerMock;

    /**
     * @var Logger|MockObject
     */
    protected $_loggerMock;

    /**
     * @var FilesystemAdapter|MockObject
     */
    protected $_flysystemAdapterMock;

    /**
     * @var CategoryImage
     */
    protected $_object;

    /**
     * @throws \ReflectionException
     */
    protected function setUp(): void
    {
        $this->_tmpManagerMock = $this->getMockBuilder(TmpManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['createCategoryTmp', 'getAbsoluteTmpPath'])
            ->getMock();

        $this->_managerMock = $this->getMockBuilder(Manager::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAdapter'])
            ->getMock();

        $this->_loggerMock = $this->getMockBuilder(Logger::class)
            ->disableOriginalConstructor()
            ->setMethods(['critical'])
            ->getMock();

        $this->_flysystemAdapterMock = $this->getMockBuilder(FilesystemAdapter::class)
            ->disableOriginalConstructor()
            ->setMethods(['getMimetype', 'getSize'])
            ->getMock();


        $this->_managerMock->expects($this->any())
            ->method('getAdapter')
            ->willReturn($this->_flysystemAdapterMock);

        $this->_object = new CategoryImage(
            $this->_tmpManagerMock,
            $this->_managerMock,
            $this->_loggerMock
        );
    }

    public function testModifyFile(): void
    {
        $data = ['filename' => 'test.jpg'];

        $file = [
            'name' => basename($data['filename']),
            'type' => 'mimetype/test',
            'tmp_name' => 'test/path/'.$data['filename'],
            'error' => 0,
            'size' => 100
        ];

        $result = [
            'name' => basename($data['filename']),
            'url' => 'test.test/'.$data['filename']
        ];

        $this->_flysystemAdapterMock->expects($this->once())
            ->method('getMimetype')
            ->with($data['filename'])
            ->willReturn($file['type']);

        $this->_tmpManagerMock->expects($this->once())
            ->method('getAbsoluteTmpPath')
            ->with($data['filename'])
            ->willReturn($file['tmp_name']);

        $this->_flysystemAdapterMock->expects($this->once())
            ->method('getSize')
            ->with($data['filename'])
            ->willReturn($file['size']);

        $this->_tmpManagerMock->expects($this->once())
            ->method('createCategoryTmp')
            ->with($file)
            ->willReturn($result);

        $this->assertEquals(json_encode($result), $this->_object->modifyFile($data));
    }

    public function testModifyFileException(): void
    {
        $data = ['invalid' => null];

        $this->expectException(LocalizedException::class);
        $this->_object->modifyFile($data);
    }
}
