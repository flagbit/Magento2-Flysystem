<?php
namespace Flagbit\Flsystem\Test\Unit\Block\Adminhtml\Filesystem\Content;

use \Flagbit\Flysystem\Adapter\FilesystemAdapter;
use \Flagbit\Flysystem\Block\Adminhtml\Filesystem\Content\Files;
use \Flagbit\Flysystem\Helper\Config;
use \Flagbit\Flysystem\Helper\Filesystem;
use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Magento\Backend\Block\Template\Context;
use \Magento\Framework\Message\Collection;
use \Magento\Framework\Message\Manager as MessageManager;
use \Magento\Framework\View\Element\Messages;
use \PHPUnit\Framework\MockObject\MockObject;

/**
 * Class FilesTest
 * @package Flagbit\Flsystem\Test\Unit\Block\Filesystem\Content
 */
class FilesTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Files
     */
    protected $_object;

    /**
     * @var Context|MockObject
     */
    protected $_contextMock;

    /**
     * @var Manager|MockObject
     */
    protected $_flysystemManagerMock;

    /**
     * @var Filesystem|MockObject
     */
    protected $_flysystemHelperMock;

    /**
     * @var Config|MockObject
     */
    protected $_flysystemConfigMock;

    /**
     * @var MessageManager|MockObject
     */
    protected $_messageManagerMock;

    /**
     * @var Messages|MockObject
     */
    protected $_messagesMock;

    /**
     * @var FilesystemAdapter|MockObject
     */
    protected $_flysystemAdapterMock;

    /**
     * Setup tests
     */
    protected function setUp(): void
    {
        $this->_contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_flysystemManagerMock = $this->getMockBuilder(Manager::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAdapter'])
            ->getMock();

        $this->_flysystemHelperMock = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCurrentPath', 'idEncode', 'getShortFilename'])
            ->getMock();

        $this->_flysystemConfigMock = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->setMethods(['getSupportedFileTypes'])
            ->getMock();

        $this->_messageManagerMock = $this->getMockBuilder(MessageManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['addErrorMessage', 'getMessages'])
            ->getMock();

        $this->_messagesMock = $this->getMockBuilder(Messages::class)
            ->disableOriginalConstructor()
            ->setMethods(['setMessages', 'getGroupedHtml'])
            ->getMock();

        $this->_flysystemAdapterMock = $this->getMockBuilder(FilesystemAdapter::class)
            ->disableOriginalConstructor()
            ->setMethods(['listContents'])
            ->getMock();

        $this->_object = new Files(
            $this->_contextMock,
            $this->_flysystemManagerMock,
            $this->_flysystemHelperMock,
            $this->_flysystemConfigMock,
            $this->_messageManagerMock,
            $this->_messagesMock
        );
    }

    public function testGetFiles(): void
    {
        $path = '/';

        $expectedFilesTestArray = [
            0 => [
                'type' => 'file',
                'path' => 'test1.jpg',
                'timestamp' => 0,
                'size' => 20,
                'dirname' => '',
                'basename' => 'test1.jpg',
                'extension' => 'jpg',
                'filename' => 'test1'
            ],
            1 => [
                'type' => 'dir',
                'path' => 'test',
                'timestamp' => 0,
                'dirname' => '',
                'basename' => 'test',
                'filename' => 'test'
            ]
        ];

        $expectedReturn = [
            0 => [
                'type' => 'file',
                'path' => 'test1.jpg',
                'timestamp' => 0,
                'size' => 20,
                'dirname' => '',
                'basename' => 'test1.jpg',
                'extension' => 'jpg',
                'filename' => 'test1'
            ]
        ];

        $this->_flysystemHelperMock->expects($this->once())
            ->method('getCurrentPath')
            ->willReturn($path);

        $this->_flysystemManagerMock->expects($this->once())
            ->method('getAdapter')
            ->willReturn($this->_flysystemAdapterMock);

        $this->_flysystemAdapterMock->expects($this->once())
            ->method('listContents')
            ->with($path)
            ->willReturn($expectedFilesTestArray);

        $this->_flysystemConfigMock->expects($this->once())
            ->method('getSupportedFileTypes')
            ->willReturn(['jpg']);

        $this->assertEquals($expectedReturn, $this->_object->getFiles());
    }

    public function testGetFilesException(): void
    {
        $path = 'invalid';

        $exception = new \Exception();

        $this->_flysystemHelperMock->expects($this->once())
            ->method('getCurrentPath')
            ->willReturn($path);

        $this->_flysystemManagerMock->expects($this->once())
            ->method('getAdapter')
            ->willReturn($this->_flysystemAdapterMock);

        $this->_flysystemAdapterMock->expects($this->once())
            ->method('listContents')
            ->with($path)
            ->willThrowException($exception);

        $this->_messageManagerMock->expects($this->once())
            ->method('addErrorMessage')
            ->with($exception->getMessage());

        $this->assertEquals([], $this->_object->getFiles());
    }

    public function testValidateFileValid(): void
    {
        $validateFile = [
            'type' => 'file',
            'path' => 'test1.jpg',
            'timestamp' => 0,
            'size' => 20,
            'dirname' => '',
            'basename' => 'test1.jpg',
            'extension' => 'jpg',
            'filename' => 'test1'
        ];

        $this->_flysystemConfigMock->expects($this->once())
            ->method('getSupportedFileTypes')
            ->willReturn(['jpg']);

        $this->assertEquals(true, $this->_object->validateFile($validateFile));
    }

    public function testValidateFileInvalid(): void
    {
        $validateFile = [
            'type' => 'file',
            'path' => 'test1.invalid',
            'timestamp' => 0,
            'size' => 20,
            'dirname' => '',
            'basename' => 'test1.invalid',
            'extension' => 'invalid',
            'filename' => 'test1'
        ];

        $this->_flysystemConfigMock->expects($this->once())
            ->method('getSupportedFileTypes')
            ->willReturn(['jpg']);

        $this->assertEquals(false, $this->_object->validateFile($validateFile));
    }

    public function testValidateFileException(): void
    {
        $validateFile = [
            'invalid' => 'invalid'
        ];

        $this->expectException(\Exception::class);
        $this->_object->validateFile($validateFile);
    }

    public function testGetMessages(): void
    {
        $messageCollection = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $messagesHtml = 'messageHtml';

        $this->_messageManagerMock->expects($this->once())
            ->method('getMessages')
            ->willReturn($messageCollection);

        $this->_messagesMock->expects($this->once())
            ->method('setMessages')
            ->with($messageCollection);

        $this->_messagesMock->expects($this->once())
            ->method('getGroupedHtml')
            ->willReturn($messagesHtml);

        $this->assertEquals($messagesHtml, $this->_object->getMessages());
    }

    public function testGetFilesCount(): void
    {
        $files = ['file1', 'file2'];
        $this->_object->setFilesCollection($files);

        $this->assertEquals(2, $this->_object->getFilesCount());
    }

    public function testGetFileId(): void
    {
        $file = [
            'path' => '/path/to/file.txt'
        ];
        $encodedFile = 'test';

        $this->_flysystemHelperMock->expects($this->once())
            ->method('idEncode')
            ->with($file['path'])
            ->willReturn($encodedFile);

        $this->assertEquals($encodedFile, $this->_object->getFileId($file));
    }

    public function testGetFileIdInvalid(): void
    {
        $file = ['invalid' => null];

        $this->assertEquals(null, $this->_object->getFileId($file));
    }

    public function testGetFileShortName(): void
    {
        $file = [
            'path' => '/path/to/file.txt'
        ];
        $shortName = 'file.txt';

        $this->_flysystemHelperMock->expects($this->once())
            ->method('getShortFilename')
            ->with($file['path'])
            ->willReturn($shortName);

        $this->assertEquals($shortName, $this->_object->getFileShortName($file));
    }

    public function testGetFileShortNameInvalid(): void
    {
        $file = ['invalid' => null];

        $this->assertEquals(null, $this->_object->getFileShortName($file));
    }

    public function testGetFileEndingInvalid(): void
    {
        $file = [
            'extension' => null
        ];

        $this->assertEquals('unknown', $this->_object->getFileEnding($file));
    }

    public function testGetFileSize(): void
    {
        $file = ['size' => 0];

        $this->assertEquals('0 Byte', $this->_object->getFileSize($file));

        $file['size'] = 1000;
        $this->assertEquals('1000 Byte', $this->_object->getFileSize($file));

        $file['size'] = 1024;
        $this->assertEquals('1 KB', $this->_object->getFileSize($file));

        $file['size'] = 5000;
        $this->assertEquals('4.88 KB', $this->_object->getFileSize($file));

        $file['size'] = 5000000;
        $this->assertEquals('4.77 MB', $this->_object->getFileSize($file));
    }

    public function testGetFileSizeInvalid(): void
    {
        $file = ['invalid' => null];

        $this->assertEquals('', $this->_object->getFileSize($file));
    }

    public function testGetLastModified(): void
    {
        $file = ['timestamp' => 1345123434234];
        $date = date('d-m-Y H:i', $file['timestamp']);

        $this->assertEquals($date, $this->_object->getLastModified($file));
    }

    public function testGetLastModifiedInvalid(): void
    {
        $file = ['invalid' => null];

        $this->assertEquals('', $this->_object->getLastModified($file));
    }
}