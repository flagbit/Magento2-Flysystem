<?php
namespace Flagbit\Flysystem\Test\Unit\Model\Config\Source;

use \Flagbit\Flysystem\Adapter\FilesystemAdapterFactory;
use \Flagbit\Flysystem\Adapter\FilesystemManager;
use \Flagbit\Flysystem\Adapter\FilesystemAdapter;
use \Flagbit\Flysystem\Helper\Config;
use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Magento\Backend\Model\Session;
use \Magento\Framework\Event\Manager as EventManager;
use \Magento\Framework\Exception\LocalizedException;
use \Magento\Framework\Logger\Monolog;
use \PHPUnit\Framework\MockObject\MockObject;

use \League\Flysystem\Adapter\NullAdapter as NullAdapter;
use \League\Flysystem\Adapter\Local as LocalAdapter;
use \League\Flysystem\Adapter\Ftp as FtpAdapter;

class ManagerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var FilesystemManager|MockObject
     */
    protected $flysystemManagerMock;

    /**
     * @var FilesystemAdapterFactory|MockObject
     */
    protected $flysystemFactoryMock;

    /**
     * @var FilesystemAdapter|MockObject
     */
    protected $flysystemAdapterMock;

    /**
     * @var EventManager|MockObject
     */
    protected $eventManagerMock;

    /**
     * @var Config|MockObject
     */
    protected $configMock;

    /**
     * @var Session|MockObject
     */
    protected $sessionMock;

    /**
     * @var Monolog|MockObject
     */
    protected $loggerMock;

    /**
     * @var NullAdapter|MockObject
     */
    protected $nullAdapterMock;

    /**
     * @var LocalAdapter|MockObject
     */
    protected $localAdapterMock;

    /**
     * @var FtpAdapter|MockObject
     */
    protected $ftpAdapterMock;

    /**
     * @var Manager|MockObject
     */
    protected $manager;

    /**
     * Set up Unit Tests
     */
    public function setUp()
    {
        $this->flysystemManagerMock = $this->getMockBuilder(FilesystemManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['createLocalDriver', 'createFtpDriver', 'createNullDriver'])
            ->getMock();

        $this->flysystemFactoryMock = $this->getMockBuilder(FilesystemAdapterFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->flysystemAdapterMock = $this->getMockBuilder(FilesystemAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->eventManagerMock = $this->getMockBuilder(EventManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['dispatch'])
            ->getMock();

        $this->configMock = $this->getMockBuilder(Config::class)
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

        $this->sessionMock = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->loggerMock = $this->getMockBuilder(Monolog::class)
            ->disableOriginalConstructor()
            ->setMethods(['critical'])
            ->getMock();

        $this->nullAdapterMock = $this->getMockBuilder(NullAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->localAdapterMock = $this->getMockBuilder(LocalAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->ftpAdapterMock = $this->getMockBuilder(FtpAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->manager = new Manager(
                $this->flysystemManagerMock,
                $this->flysystemFactoryMock,
                $this->eventManagerMock,
                $this->configMock,
                $this->sessionMock,
                $this->loggerMock
            );
    }

    /**
     * Test create method without source
     */
    public function testCreate()
    {
        $testSource = null;

        $this->configMock->expects($this->once())
            ->method('getSource')
            ->willReturn('test');

        // setAdapter
        $this->flysystemManagerMock->expects($this->once())
            ->method('createNullDriver')
            ->willReturn($this->nullAdapterMock);

        $this->flysystemFactoryMock->expects($this->once())
            ->method('create')
            ->with($this->nullAdapterMock)
            ->willReturn($this->flysystemAdapterMock);

        $this->eventManagerMock->expects($this->once())
            ->method('dispatch')
            ->withAnyParameters();

        $this->assertEquals($this->flysystemAdapterMock, $this->manager->create($testSource));
    }

    /**
     * Test create method with invalid source param
     */
    public function testCreateInvalidSource()
    {
        $testSource = 'invalid';

        $this->configMock->expects($this->never())
            ->method('getSource');

        $this->flysystemFactoryMock->expects($this->never())
            ->method('create');

        $this->eventManagerMock->expects($this->once())
            ->method('dispatch')
            ->withAnyParameters();

        $this->assertEquals(null, $this->manager->create($testSource));
    }

    /**
     * Test creation of local adapter without path param
     */
    public function testCreateLocalAdapter()
    {
        $pathParam = null;
        $pathConfig = '/';

        $this->configMock->expects($this->once())
            ->method('getLocalPath')
            ->willReturn($pathConfig);

        $this->flysystemManagerMock->expects($this->once())
            ->method('createLocalDriver')
            ->with($pathConfig)
            ->willReturn($this->localAdapterMock);

        $this->flysystemFactoryMock->expects($this->once())
            ->method('create')
            ->with($this->localAdapterMock)
            ->willReturn($this->flysystemAdapterMock);

        $this->assertEquals($this->flysystemAdapterMock, $this->manager->createLocalAdapter($pathParam));
    }

    /**
     * Test creation of local adapter with invalid path param
     */
    public function testCreateLocalAdapterException()
    {
        $pathParam = 'invalidPath';
        $exception = new \Exception();

        $this->configMock->expects($this->never())
            ->method('getLocalPath');

        $this->flysystemManagerMock->expects($this->once())
            ->method('createLocalDriver')
            ->with($pathParam)
            ->willThrowException($exception);

        $this->loggerMock->expects($this->once())
            ->method('critical')
            ->with($exception->getMessage())
            ->willReturn(true);

        $this->assertEquals(null, $this->manager->createLocalAdapter($pathParam));
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

        $this->configMock->expects($this->once())
            ->method('getFtpHost')
            ->willReturn($configArray['host']);

        $this->configMock->expects($this->once())
            ->method('getFtpUser')
            ->willReturn($configArray['username']);

        $this->configMock->expects($this->once())
            ->method('getFtpPassword')
            ->willReturn($configArray['password']);

        $this->configMock->expects($this->once())
            ->method('getFtpPath')
            ->willReturn($configArray['root']);

        $this->configMock->expects($this->once())
            ->method('getFtpPort')
            ->willReturn($configArray['port']);

        $this->configMock->expects($this->once())
            ->method('getFtpPassive')
            ->willReturn($configArray['passive']);

        $this->configMock->expects($this->once())
            ->method('getFtpSsl')
            ->willReturn($configArray['ssl']);

        $this->configMock->expects($this->once())
            ->method('getFtpTimeout')
            ->willReturn($configArray['timeout']);

        $this->flysystemManagerMock->expects($this->once())
            ->method('createFtpDriver')
            ->with($configArray)
            ->willReturn($this->ftpAdapterMock);

        $this->flysystemFactoryMock->expects($this->once())
            ->method('create')
            ->with($this->ftpAdapterMock)
            ->willReturn($this->flysystemAdapterMock);

        $this->eventManagerMock->expects($this->once())
            ->method('dispatch')
            ->withAnyParameters();

        $this->assertEquals($this->flysystemAdapterMock, $this->manager->create($testSource));
    }

    /**
     * Test create ftp adapter with invalid ftp connection data
     */
    public function testCreateFtpAdapterInvalid1()
    {
        $testSource = 'ftp';

        $exception = new LocalizedException(__('FTP connection is not possible. Please check your configuration.'));

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

        $this->configMock->expects($this->once())
            ->method('getFtpHost')
            ->willReturn($configArray['host']);

        $this->configMock->expects($this->once())
            ->method('getFtpUser')
            ->willReturn($configArray['username']);

        $this->configMock->expects($this->once())
            ->method('getFtpPassword')
            ->willReturn($configArray['password']);

        $this->configMock->expects($this->never())
            ->method('getFtpPath');

        $this->loggerMock->expects($this->once())
            ->method('critical')
            ->with($exception->getMessage())
            ->willReturn(true);

        $this->eventManagerMock->expects($this->once())
            ->method('dispatch')
            ->withAnyParameters();

        $this->assertEquals(null, $this->manager->create($testSource));
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

        $this->configMock->expects($this->once())
            ->method('getFtpHost')
            ->willReturn($configArray['host']);

        $this->configMock->expects($this->once())
            ->method('getFtpUser')
            ->willReturn($configArray['username']);

        $this->configMock->expects($this->once())
            ->method('getFtpPassword')
            ->willReturn($configArray['password']);

        $this->configMock->expects($this->once())
            ->method('getFtpPath')
            ->willReturn($configArray['root']);

        $this->configMock->expects($this->once())
            ->method('getFtpPort')
            ->willReturn($configArray['port']);

        $this->configMock->expects($this->once())
            ->method('getFtpPassive')
            ->willReturn($configArray['passive']);

        $this->configMock->expects($this->once())
            ->method('getFtpSsl')
            ->willReturn($configArray['ssl']);

        $this->configMock->expects($this->once())
            ->method('getFtpTimeout')
            ->willReturn($configArray['timeout']);

        $this->flysystemManagerMock->expects($this->once())
            ->method('createFtpDriver')
            ->with($configArray)
            ->willThrowException($exception);

        $this->loggerMock->expects($this->once())
            ->method('critical')
            ->with($exception->getMessage())
            ->willReturn(true);

        $this->eventManagerMock->expects($this->once())
            ->method('dispatch')
            ->withAnyParameters();

        $this->assertEquals(null, $this->manager->create($testSource));
    }
}