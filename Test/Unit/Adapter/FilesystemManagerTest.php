<?php
namespace Flagbit\Flysystem\Test\Unit\Adapter;

use \Flagbit\Flysystem\Adapter\FilesystemManager;
use \League\Flysystem\Adapter\Ftp;
use \League\Flysystem\Adapter\Local;
use \League\Flysystem\Adapter\NullAdapter;
use \Magento\Framework\App\ObjectManager;
use \PHPUnit\Framework\MockObject\MockObject;
use \PHPUnit\Framework\TestCase;

class FilesystemManagerTest extends TestCase
{
    /**
     * @var ObjectManager|MockObject
     */
    protected $_objectManagerMock;

    /**
     * @var Ftp|MockObject
     */
    protected $_ftpAdapterMock;

    /**
     * @var Local|MockObject
     */
    protected $_localAdapterMock;

    /**
     * @var NullAdapter|MockObject
     */
    protected $_nullAdapterMock;

    /**
     * @var FilesystemManager
     */
    protected $_object;

    public function setUp()
    {
        $this->_objectManagerMock = $this->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->_ftpAdapterMock = $this->getMockBuilder(Ftp::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_localAdapterMock = $this->getMockBuilder(Local::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_nullAdapterMock = $this->getMockBuilder(NullAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_object = new FilesystemManager(
            $this->_objectManagerMock
        );
    }

    public function testCreateFtpDriver()
    {
        $config = [
            'config' => []
        ];

        $this->_objectManagerMock->expects($this->once())
            ->method('create')
            ->with(Ftp::class, $this->arrayHasKey('config'))
            ->willReturn($this->_ftpAdapterMock);

        $this->assertEquals($this->_ftpAdapterMock, $this->_object->createFtpDriver($config));
    }

    public function testCreateLocalDriver()
    {
        $root = 'test';
        
        $this->_objectManagerMock->expects($this->once())
            ->method('create')
            ->with(Local::class, $this->arrayHasKey('root'))
            ->willReturn($this->_localAdapterMock);
        
        $this->assertEquals($this->_localAdapterMock, $this->_object->createLocalDriver($root));
    }
    
    public function testCreateLocalDriverException()
    {
        $root = 'invalid';
        
        $this->_objectManagerMock->expects($this->once())
            ->method('create')
            ->with(Local::class, $this->arrayHasKey('root'))
            ->willThrowException(new \LogicException());
        
        $this->expectException(\LogicException::class);
        $this->assertEquals($this->_localAdapterMock, $this->_object->createLocalDriver($root));
    }
    
    public function testCreateNullDriver()
    {
        $this->_objectManagerMock->expects($this->once())
            ->method('create')
            ->with(NullAdapter::class)
            ->willReturn($this->_nullAdapterMock);
        
        $this->assertEquals($this->_nullAdapterMock, $this->_object->createNullDriver());
    }

}