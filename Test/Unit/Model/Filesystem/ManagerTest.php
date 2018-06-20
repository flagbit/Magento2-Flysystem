<?php
namespace Flagbit\Flysystem\Test\Unit\Model\Config\Source;

use \Flagbit\Flysystem\Adapter\FilesystemAdapterFactory;
use \Flagbit\Flysystem\Adapter\FilesystemManager;
use \Flagbit\Flysystem\Adapter\FilesystemAdapter;
use \Flagbit\Flysystem\Helper\Config;
use \Flagbit\Flysystem\Helper\Errors;
use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Magento\Backend\Model\Session;
use \Magento\Framework\Event\Manager as EventManager;
use \Magento\Framework\Exception\LocalizedException;
use \Magento\Framework\Logger\Monolog;
use \PHPUnit\Framework\MockObject\MockObject;

use \League\Flysystem\Adapter\NullAdapter as NullAdapter;
use \League\Flysystem\Adapter\Local as LocalAdapter;
use \League\Flysystem\Adapter\Ftp as FtpAdapter;

/**
 * Class ManagerTest
 * @package Flagbit\Flysystem\Test\Unit\Model\Config\Source
 */
class ManagerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var FilesystemManager|MockObject
     */
    protected $_flysystemManagerMock;

    /**
     * @var FilesystemAdapterFactory|MockObject
     */
    protected $_flysystemFactoryMock;

    /**
     * @var FilesystemAdapter|MockObject
     */
    protected $_flysystemAdapterMock;

    /**
     * @var EventManager|MockObject
     */
    protected $_eventManagerMock;

    /**
     * @var Config|MockObject
     */
    protected $_configMock;

    /**
     * @var Session|MockObject
     */
    protected $_sessionMock;

    /**
     * @var Monolog|MockObject
     */
    protected $_loggerMock;

    /**
     * @var NullAdapter|MockObject
     */
    protected $_nullAdapterMock;

    /**
     * @var LocalAdapter|MockObject
     */
    protected $_localAdapterMock;

    /**
     * @var FtpAdapter|MockObject
     */
    protected $_ftpAdapterMock;

    /**
     * @var Manager|MockObject
     */
    protected $_manager;

    /**
     * Set up Unit Tests
     */
    public function setUp()
    {
        $this->_flysystemManagerMock = $this->getMockBuilder(FilesystemManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['createLocalDriver', 'createFtpDriver', 'createNullDriver'])
            ->getMock();

        $this->_flysystemFactoryMock = $this->getMockBuilder(FilesystemAdapterFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->_flysystemAdapterMock = $this->getMockBuilder(FilesystemAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_eventManagerMock = $this->getMockBuilder(EventManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['dispatch'])
            ->getMock();

        $this->_configMock = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getSource',
                'getLocalPath',
                'getFtpHost',
                'getFtpUser',
                'getFtpPassword',
                'getFtpPath',
                'getFtpPort',
                'getFtpPassive',
                'getFtpSsl',
                'getFtpTimeout'
            ])
            ->getMock();

        $this->_sessionMock = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_loggerMock = $this->getMockBuilder(Monolog::class)
            ->disableOriginalConstructor()
            ->setMethods(['critical'])
            ->getMock();

        $this->_nullAdapterMock = $this->getMockBuilder(NullAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_localAdapterMock = $this->getMockBuilder(LocalAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_ftpAdapterMock = $this->getMockBuilder(FtpAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_manager = new Manager(
                $this->_flysystemManagerMock,
                $this->_flysystemFactoryMock,
                $this->_eventManagerMock,
                $this->_configMock,
                $this->_sessionMock,
                $this->_loggerMock
            );
    }

    /**
     * Test create method without source
     */
    public function testCreate()
    {
        $testSource = null;

        $this->_configMock->expects($this->once())
            ->method('getSource')
            ->willReturn('test');

        // setAdapter
        $this->_flysystemManagerMock->expects($this->once())
            ->method('createNullDriver')
            ->willReturn($this->_nullAdapterMock);

        $this->_flysystemFactoryMock->expects($this->once())
            ->method('create')
            ->with($this->_nullAdapterMock)
            ->willReturn($this->_flysystemAdapterMock);

        $this->_eventManagerMock->expects($this->once())
            ->method('dispatch')
            ->withAnyParameters();

        $this->assertEquals($this->_flysystemAdapterMock, $this->_manager->create($testSource));
    }

    /**
     * Test create method with invalid source param
     */
    public function testCreateInvalidSource()
    {
        $testSource = 'invalid';

        $this->_configMock->expects($this->never())
            ->method('getSource');

        $this->_flysystemFactoryMock->expects($this->never())
            ->method('create');

        $this->_eventManagerMock->expects($this->once())
            ->method('dispatch')
            ->withAnyParameters();

        $this->assertEquals(null, $this->_manager->create($testSource));
    }

    /**
     * Test creation of local adapter without path param
     */
    public function testCreateLocalAdapter()
    {
        $pathParam = null;
        $pathConfig = '/';

        $this->_configMock->expects($this->once())
            ->method('getLocalPath')
            ->willReturn($pathConfig);

        $this->_flysystemManagerMock->expects($this->once())
            ->method('createLocalDriver')
            ->with($pathConfig)
            ->willReturn($this->_localAdapterMock);

        $this->_flysystemFactoryMock->expects($this->once())
            ->method('create')
            ->with($this->_localAdapterMock)
            ->willReturn($this->_flysystemAdapterMock);

        $this->assertEquals($this->_flysystemAdapterMock, $this->_manager->createLocalAdapter($pathParam));
    }

    /**
     * Test creation of local adapter with invalid path param
     */
    public function testCreateLocalAdapterException()
    {
        $pathParam = 'invalidPath';
        $exception = new \Exception();

        $this->_configMock->expects($this->never())
            ->method('getLocalPath');

        $this->_flysystemManagerMock->expects($this->once())
            ->method('createLocalDriver')
            ->with($pathParam)
            ->willThrowException($exception);

        $this->_loggerMock->expects($this->once())
            ->method('critical')
            ->with($exception->getMessage())
            ->willReturn(true);

        $this->assertEquals(null, $this->_manager->createLocalAdapter($pathParam));
    }

    /**
     * Test creation of ftp adapter (via create method because createFtpAdapter is protected)
     */
    public function testCreateFtpAdapter()
    {
        $testSource = 'ftp';

        $configArray = [
            'host' => 'ftphost',
            'username' => 'ftpuser',
            'password' => 'ftppassword',
            'port' => 22,
            'root' => 'ftppath',
            'passive' => true,
            'ssl' => true,
            'timeout' => 30
        ];

        $this->_configMock->expects($this->once())
            ->method('getFtpHost')
            ->willReturn($configArray['host']);

        $this->_configMock->expects($this->once())
            ->method('getFtpUser')
            ->willReturn($configArray['username']);

        $this->_configMock->expects($this->once())
            ->method('getFtpPassword')
            ->willReturn($configArray['password']);

        $this->_configMock->expects($this->once())
            ->method('getFtpPath')
            ->willReturn($configArray['root']);

        $this->_configMock->expects($this->once())
            ->method('getFtpPort')
            ->willReturn($configArray['port']);

        $this->_configMock->expects($this->once())
            ->method('getFtpPassive')
            ->willReturn($configArray['passive']);

        $this->_configMock->expects($this->once())
            ->method('getFtpSsl')
            ->willReturn($configArray['ssl']);

        $this->_configMock->expects($this->once())
            ->method('getFtpTimeout')
            ->willReturn($configArray['timeout']);

        $this->_flysystemManagerMock->expects($this->once())
            ->method('createFtpDriver')
            ->with($configArray)
            ->willReturn($this->_ftpAdapterMock);

        $this->_flysystemFactoryMock->expects($this->once())
            ->method('create')
            ->with($this->_ftpAdapterMock)
            ->willReturn($this->_flysystemAdapterMock);

        $this->_eventManagerMock->expects($this->once())
            ->method('dispatch')
            ->withAnyParameters();

        $this->assertEquals($this->_flysystemAdapterMock, $this->_manager->create($testSource));
    }

    /**
     * Test create ftp adapter with invalid ftp connection data
     */
    public function testCreateFtpAdapterInvalid1()
    {
        $testSource = 'ftp';

        $exception = new LocalizedException(__(Errors::getErrorMessage(101)));

        $configArray = [
            'host' => null,
            'username' => null,
            'password' => null,
            'port' => 0,
            'root' => '',
            'passive' => false,
            'ssl' => false,
            'timeout' => 0
        ];

        $this->_configMock->expects($this->once())
            ->method('getFtpHost')
            ->willReturn($configArray['host']);

        $this->_configMock->expects($this->once())
            ->method('getFtpUser')
            ->willReturn($configArray['username']);

        $this->_configMock->expects($this->once())
            ->method('getFtpPassword')
            ->willReturn($configArray['password']);

        $this->_configMock->expects($this->never())
            ->method('getFtpPath');

        $this->_loggerMock->expects($this->once())
            ->method('critical')
            ->with($exception->getMessage())
            ->willReturn(true);

        $this->_eventManagerMock->expects($this->once())
            ->method('dispatch')
            ->withAnyParameters();

        $this->assertEquals(null, $this->_manager->create($testSource));
    }

    /**
     * Test create ftp adapter
     */
    public function testCreateFtpAdapterInvalid2()
    {
        $testSource = 'ftp';

        $exception = new \Exception();

        $configArray = [
            'host' => 'ftphost',
            'username' => 'ftpuser',
            'password' => 'ftppassword',
            'port' => 22,
            'root' => 'invalidpath',
            'passive' => true,
            'ssl' => true,
            'timeout' => 30
        ];

        $this->_configMock->expects($this->once())
            ->method('getFtpHost')
            ->willReturn($configArray['host']);

        $this->_configMock->expects($this->once())
            ->method('getFtpUser')
            ->willReturn($configArray['username']);

        $this->_configMock->expects($this->once())
            ->method('getFtpPassword')
            ->willReturn($configArray['password']);

        $this->_configMock->expects($this->once())
            ->method('getFtpPath')
            ->willReturn($configArray['root']);

        $this->_configMock->expects($this->once())
            ->method('getFtpPort')
            ->willReturn($configArray['port']);

        $this->_configMock->expects($this->once())
            ->method('getFtpPassive')
            ->willReturn($configArray['passive']);

        $this->_configMock->expects($this->once())
            ->method('getFtpSsl')
            ->willReturn($configArray['ssl']);

        $this->_configMock->expects($this->once())
            ->method('getFtpTimeout')
            ->willReturn($configArray['timeout']);

        $this->_flysystemManagerMock->expects($this->once())
            ->method('createFtpDriver')
            ->with($configArray)
            ->willThrowException($exception);

        $this->_loggerMock->expects($this->once())
            ->method('critical')
            ->with($exception->getMessage())
            ->willReturn(true);

        $this->_eventManagerMock->expects($this->once())
            ->method('dispatch')
            ->withAnyParameters();

        $this->assertEquals(null, $this->_manager->create($testSource));
    }
}