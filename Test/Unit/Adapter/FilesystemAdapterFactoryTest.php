<?php
namespace Flagbit\Flysystem\Test\Unit\Adapter;

use \Flagbit\Flysystem\Adapter\FilesystemAdapter;
use \Flagbit\Flysystem\Adapter\FilesystemAdapterFactory;
use \League\Flysystem\Adapter\Local;
use \League\Flysystem\Filesystem;
use \League\Flysystem\FilesystemFactory;
use \Magento\Framework\App\ObjectManager;
use \PHPUnit\Framework\MockObject\MockObject;
use \PHPUnit\Framework\TestCase;

class FilesystemAdapterFactoryTest extends TestCase
{
    /**
     * @var ObjectManager|MockObject
     */
    protected $_objectManagerMock;

    /**
     * @var Filesystem|MockObject
     */
    protected $_flysystemMock;

    /**
     * @var FilesystemFactory|MockObject
     */
    protected $_flysystemFactoryMock;

    /**
     * @var FilesystemAdapter|MockObject
     */
    protected $_flysystemAdapterMock;

    /**
     * @var FilesystemAdapterFactory
     */
    protected $_object;


    protected function setUp(): void
    {
        $this->_objectManagerMock = $this->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->_flysystemMock = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_flysystemFactoryMock = $this->getMockBuilder(FilesystemFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->_flysystemAdapterMock = $this->getMockBuilder(FilesystemAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_object = new FilesystemAdapterFactory(
            $this->_objectManagerMock,
            $this->_flysystemFactoryMock
        );
    }


    public function testCreate(): void
    {
        /** @var Local|MockObject $adapterMock */
        $adapterMock = $this->getMockBuilder(Local::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_flysystemFactoryMock->expects($this->once())
            ->method('create')
            ->withAnyParameters()
            ->willReturn($this->_flysystemMock);

        $this->_objectManagerMock->expects($this->once())
            ->method('create')
            ->with(FilesystemAdapter::class, ['filesystem' => $this->_flysystemMock])
            ->willReturn($this->_flysystemAdapterMock);

        $this->assertEquals($this->_flysystemAdapterMock, $this->_object->create($adapterMock, []));
    }

    public function testCreateInvalidConfig(): void
    {
        /** @var Local|MockObject $adapterMock */
        $adapterMock = $this->getMockBuilder(Local::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_flysystemFactoryMock->expects($this->once())
            ->method('create')
            ->withAnyParameters()
            ->willThrowException(new \LogicException());

        $this->expectException(\LogicException::class);
        $this->_object->create($adapterMock, 'invalid');
    }
}