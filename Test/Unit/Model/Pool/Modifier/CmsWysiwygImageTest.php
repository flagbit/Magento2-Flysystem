<?php
namespace Flagbit\Flysystem\Test\Unit\Model\Pool\Modifier;

use \Flagbit\Flysystem\Adapter\FilesystemAdapter;
use \Flagbit\Flysystem\Helper\Filesystem;
use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Flagbit\Flysystem\Model\Filesystem\TmpManager;
use \Flagbit\Flysystem\Model\Pool\Modifier\CmsWysiwygImage;
use \Magento\Framework\Exception\LocalizedException;
use \Magento\Framework\Filesystem\Directory\Write;
use \Monolog\Logger;
use \PHPUnit\Framework\MockObject\MockObject;

/**
 * Class CmsWysiwygImageTest
 * @package Flagbit\Flysystem\Test\Unit\Model\Pool\Modifier
 */
class CmsWysiwygImageTest extends \PhpUnit\Framework\TestCase
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
     * @var Filesystem|MockObject
     */
    protected $_filesystemHelperMock;

    /**
     * @var Logger|MockObject
     */
    protected $_loggerMock;

    /**
     * @var FilesystemAdapter|MockObject
     */
    protected $_flysystemAdapterMock;

    /**
     * @var Write|MockObject
     */
    protected $_directoryListMock;

    /**
     * @var CmsWysiwygImage
     */
    protected $_object;

    /**
     * @throws \ReflectionException
     */
    protected function setUp(): void
    {
        $this->_tmpManagerMock = $this->getMockBuilder(TmpManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['writeWysiwygFile', 'getDirectoryListMedia'])
            ->getMock();

        $this->_managerMock = $this->getMockBuilder(Manager::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAdapter', 'getPath'])
            ->getMock();

        $this->_filesystemHelperMock = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->setMethods(['getImageHtmlDeclaration'])
            ->getMock();

        $this->_loggerMock = $this->getMockBuilder(Logger::class)
            ->disableOriginalConstructor()
            ->setMethods(['critical'])
            ->getMock();

        $this->_flysystemAdapterMock = $this->getMockBuilder(FilesystemAdapter::class)
            ->disableOriginalConstructor()
            ->setMethods(['read'])
            ->getMock();

        $this->_directoryListMock = $this->getMockBuilder(Write::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAbsolutePath'])
            ->getMock();

        $this->_managerMock->expects($this->any())
            ->method('getAdapter')
            ->willReturn($this->_flysystemAdapterMock);

        $this->_object = new CmsWysiwygImage(
            $this->_managerMock,
            $this->_tmpManagerMock,
            $this->_filesystemHelperMock,
            $this->_loggerMock
        );
    }

    public function testModifyFile(): void
    {
        $data = ['filename' => 'test/test.jpg', 'as_is' => true];
        $content = 'test';

        $managerPath = '/magento/test/';
        $mediaPath = '/magento/pub/media/';

        $newFile = 'wysiwyg/test.jpg';
        $imageHtml = 'imageHtml';

        $this->_flysystemAdapterMock->expects($this->once())
            ->method('read')
            ->with($data['filename'])
            ->willReturn($content);

        $this->_managerMock->expects($this->once())
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

        $this->_filesystemHelperMock->expects($this->once())
            ->method('getImageHtmlDeclaration')
            ->with($newFile, $data['as_is'])
            ->willReturn($imageHtml);

        $this->assertEquals($imageHtml, $this->_object->modifyFile($data));
    }

    public function testModifyMediaFile(): void
    {
        $data = ['filename' => 'media/wysiwyg/test.jpg', 'as_is' => true];
        $content = 'test';

        $managerPath = '/magento/pub/';
        $mediaPath = '/magento/pub/media/';

        $newFile = 'wysiwyg/test.jpg';
        $imageHtml = 'imageHtml';

        $this->_flysystemAdapterMock->expects($this->once())
            ->method('read')
            ->with($data['filename'])
            ->willReturn($content);

        $this->_managerMock->expects($this->once())
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

        $this->_filesystemHelperMock->expects($this->once())
            ->method('getImageHtmlDeclaration')
            ->with($newFile, $data['as_is'])
            ->willReturn($imageHtml);

        $this->assertEquals($imageHtml, $this->_object->modifyFile($data));
    }

    public function testModifyFileException(): void
    {
        $data = ['invalid' => null];

        $this->expectException(LocalizedException::class);
        $this->_object->modifyFile($data);
    }
}
