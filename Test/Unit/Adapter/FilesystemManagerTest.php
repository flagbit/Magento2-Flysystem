<?php
namespace Flagbit\Flysystem\Test\Unit\Adapter;

use \Flagbit\Flysystem\Adapter\FilesystemManager;
use \League\Flysystem\Adapter\Ftp;
use \League\Flysystem\Adapter\FtpFactory;
use \League\Flysystem\Sftp\SftpAdapter;
use \League\Flysystem\Sftp\SftpAdapterFactory;
use \League\Flysystem\Adapter\Local;
use \League\Flysystem\Adapter\LocalFactory;
use \League\Flysystem\Adapter\NullAdapter;
use \League\Flysystem\Adapter\NullAdapterFactory;
use \PHPUnit\Framework\MockObject\MockObject;
use \PHPUnit\Framework\TestCase;

class FilesystemManagerTest extends TestCase
{
    /**
     * @var Ftp|MockObject
     */
    protected $_ftpAdapterMock;

    /**
     * @var FtpFactory|MockObject
     */
    protected $_ftpAdapterFactoryMock;

    /**
     * @var SftpAdapter|MockObject
     */
    protected $_sftpAdapterMock;

    /**
     * @var SftpAdapterFactory|MockObject
     */
    protected $_sftpAdapterFactoryMock;

    /**
     * @var Local|MockObject
     */
    protected $_localAdapterMock;

    /**
     * @var LocalFactory|MockObject
     */
    protected $_localAdapterFactoryMock;

    /**
     * @var NullAdapter|MockObject
     */
    protected $_nullAdapterMock;

    /**
     * @var NullAdapterFactory|MockObject
     */
    protected $_nullAdapterFactoryMock;

    /**
     * @var FilesystemManager
     */
    protected $_object;

    protected function setUp(): void
    {

        $this->_ftpAdapterMock = $this->getMockBuilder(Ftp::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_ftpAdapterFactoryMock = $this->getMockBuilder(FtpFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->_sftpAdapterMock = $this->getMockBuilder(SftpAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_sftpAdapterFactoryMock = $this->getMockBuilder(SftpAdapterFActory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->_localAdapterMock = $this->getMockBuilder(Local::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_localAdapterFactoryMock = $this->getMockBuilder(LocalFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->_nullAdapterMock = $this->getMockBuilder(NullAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_nullAdapterFactoryMock = $this->getMockBuilder(NullAdapterFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->_object = new FilesystemManager(
            $this->_localAdapterFactoryMock,
            $this->_ftpAdapterFactoryMock,
            $this->_nullAdapterFactoryMock,
            $this->_sftpAdapterFactoryMock
        );
    }

    public function testCreateFtpDriver(): void
    {
        $config = [
            'config' => []
        ];

        $this->_ftpAdapterFactoryMock->expects($this->once())
            ->method('create')
            ->with($this->arrayHasKey('config'))
            ->willReturn($this->_ftpAdapterMock);

        $this->assertEquals($this->_ftpAdapterMock, $this->_object->createFtpDriver($config));
    }

    public function testCreateSftpDriver(): void
    {
        $config = [
            'config' => []
        ];

        $this->_sftpAdapterFactoryMock->expects($this->once())
            ->method('create')
            ->with($this->arrayHasKey('config'))
            ->willReturn($this->_sftpAdapterMock);

        $this->assertEquals($this->_sftpAdapterMock, $this->_object->createSftpDriver($config));
    }

    public function testCreateLocalDriver(): void
    {
        $root = 'test';
        
        $this->_localAdapterFactoryMock->expects($this->once())
            ->method('create')
            ->with($this->arrayHasKey('root'))
            ->willReturn($this->_localAdapterMock);
        
        $this->assertEquals($this->_localAdapterMock, $this->_object->createLocalDriver($root));
    }
    
    public function testCreateLocalDriverException(): void
    {
        $root = 'invalid';
        
        $this->_localAdapterFactoryMock->expects($this->once())
            ->method('create')
            ->with($this->arrayHasKey('root'))
            ->willThrowException(new \LogicException());
        
        $this->expectException(\LogicException::class);
        $this->assertEquals($this->_localAdapterMock, $this->_object->createLocalDriver($root));
    }
    
    public function testCreateNullDriver(): void
    {
        $this->_nullAdapterFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->_nullAdapterMock);
        
        $this->assertEquals($this->_nullAdapterMock, $this->_object->createNullDriver());
    }

}