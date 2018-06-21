<?php
namespace Flagbit\Flysystem\Test\Unit\Model\Filesystem;

use \Flagbit\Flysystem\Adapter\FilesystemAdapter;
use \Flagbit\Flysystem\Adapter\FilesystemAdapterFactory;
use \Flagbit\Flysystem\Adapter\FilesystemManager;
use \Flagbit\Flysystem\Helper\Config;
use \Flagbit\Flysystem\Model\Filesystem\UploadManager;
use \League\Flysystem\Adapter\Local;
use \Magento\Framework\App\ObjectManager;
use \Magento\Framework\Logger\Monolog;
use \Magento\MediaStorage\Model\File\Uploader;
use \PHPUnit\Framework\MockObject\MockObject;
use \PHPUnit\Framework\TestCase;

class UploadManagerTest extends TestCase
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
     * @var Config|MockObject
     */
    protected $_flysystemConfigMock;

    /**
     * @var Monolog|MockObject
     */
    protected $_loggerMock;

    /**
     * @var ObjectManager|MockObject
     */
    protected $_objectManagerMock;

    /**
     * @var Local|MockObject
     */
    protected $_localAdapterMock;

    /**
     * @var FilesystemAdapter|MockObject
     */
    protected $_flysystemAdapterMock;

    /**
     * @var Uploader|MockObject
     */
    protected $_uploaderMock;

    /**
     * @var UploadManager
     */
    protected $_object;


    public function setUp()
    {
        $this->_flysystemManagerMock = $this->getMockBuilder(FilesystemManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['createLocalDriver'])
            ->getMock();

        $this->_flysystemFactoryMock = $this->getMockBuilder(FilesystemAdapterFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->_flysystemConfigMock = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->setMethods(['getSupportedFileTypes'])
            ->getMock();

        $this->_loggerMock = $this->getMockBuilder(Monolog::class)
            ->disableOriginalConstructor()
            ->setMethods(['critical'])
            ->getMock();

        $this->_objectManagerMock = $this->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->_localAdapterMock = $this->getMockBuilder(Local::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_flysystemAdapterMock = $this->getMockBuilder(FilesystemAdapter::class)
            ->disableOriginalConstructor()
            ->setMethods(['read'])
            ->getMock();

        $this->_uploaderMock = $this->getMockBuilder(Uploader::class)
            ->disableOriginalConstructor()
            ->setMethods(['validateFile'])
            ->getMock();

        $this->_flysystemManagerMock->expects($this->once())
            ->method('createLocalDriver')
            ->with(UploadManager::SERVER_TMP_PATH)
            ->willReturn($this->_localAdapterMock);

        $this->_flysystemFactoryMock->expects($this->once())
            ->method('create')
            ->with($this->_localAdapterMock)
            ->willReturn($this->_flysystemAdapterMock);

        $this->_objectManagerMock->expects($this->once())
            ->method('create')
            ->with(Uploader::class, ['fileId' => Config::FLYSYSTEM_UPLOAD_ID])
            ->willReturn($this->_uploaderMock);

        $this->_object = new UploadManager(
            $this->_flysystemManagerMock,
            $this->_flysystemFactoryMock,
            $this->_flysystemConfigMock,
            $this->_loggerMock,
            $this->_objectManagerMock
        );
    }

    public function testValidateFileTypesTest()
    {
        $file = [
            'name' => 'test.jpg',
        ];

        $supportedFileTypes = ['jpg', 'png'];

        $this->_flysystemConfigMock->expects($this->once())
            ->method('getSupportedFileTypes')
            ->willReturn($supportedFileTypes);

        $this->_object->validateFileType($file);
    }

    public function testValidateFileTypesException()
    {
        $file = [
            'name' => 'test'
        ];

        $supportedFileTypes = ['jpg', 'png'];

        $this->_flysystemConfigMock->expects($this->once())
            ->method('getSupportedFileTypes')
            ->willReturn($supportedFileTypes);

        $this->expectException(\Exception::class);

        $this->_object->validateFileType($file);
    }

    public function testUpload()
    {
        /** @var FilesystemAdapter|MockObject $adapter */
        $adapter = $this->getMockBuilder(FilesystemAdapter::class)
            ->disableOriginalConstructor()
            ->setMethods(['has', 'write'])
            ->getMock();

        $targetPath = '/target/path';

        $file = [
            'name' => 'test.jpg',
            'tmp_name' => 'TEST',
            'path' => '/tmp/test.jpg'
        ];

        $content = 'filecontent';

        $this->_uploaderMock->expects($this->once())
            ->method('validateFile')
            ->willReturn($file);

        $this->_flysystemConfigMock->expects($this->once())
            ->method('getSupportedFileTypes')
            ->willReturn(['jpg']);

        $this->_flysystemAdapterMock->expects($this->once())
            ->method('read')
            ->with(basename($file['tmp_name']))
            ->willReturn($content);

        $adapter->expects($this->at(0))
            ->method('has')
            ->with($targetPath.'/'.$file['name'])
            ->willReturn(true);

        $adapter->expects($this->at(1))
            ->method('has')
            ->with($targetPath.'/test_1.jpg')
            ->willReturn(false);

        $adapter->expects($this->once())
            ->method('write')
            ->with($targetPath.'/test_1.jpg')
            ->willReturn(true);

        $this->assertEquals(true, $this->_object->upload($adapter, $targetPath));
    }

    public function testUploadException()
    {
        /** @var FilesystemAdapter|MockObject $adapter */
        $adapter = $this->getMockBuilder(FilesystemAdapter::class)
            ->disableOriginalConstructor()
            ->setMethods(['has', 'write'])
            ->getMock();

        $targetPath = '/target/path';

        $file = [
            'name' => 'test.jpg',
        ];

        $this->_uploaderMock->expects($this->once())
            ->method('validateFile')
            ->willReturn($file);

        $this->_flysystemConfigMock->expects($this->once())
            ->method('getSupportedFileTypes')
            ->willReturn(['jpg']);

        $this->expectException(\Exception::class);
        $this->_object->upload($adapter, $targetPath);
    }
}
