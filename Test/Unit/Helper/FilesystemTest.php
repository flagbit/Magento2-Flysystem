<?php
namespace Flagbit\Flysystem\Test\Unit\Helper;

use \Flagbit\Flysystem\Helper\Filesystem;
use \Magento\Backend\Model\UrlInterface;
use \Magento\Cms\Helper\Wysiwyg\Images;
use \Magento\Framework\App\Helper\Context;
use \Magento\Framework\App\Request\Http;
use \Magento\Framework\Url\Encoder;
use \Magento\Store\Model\Store;
use \Magento\Store\Model\StoreManager;
use \PHPUnit\Framework\MockObject\MockObject;

class FilesystemTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Context|MockObject
     */
    protected $_contextMock;

    /**
     * @var StoreManager|MockObject
     */
    protected $_storeManagerMock;

    /**
     * @var Images|MockObject
     */
    protected $_imageHelperMock;

    /**
     * @var Http|MockObject
     */
    protected $_requestMock;

    /**
     * @var Encoder|MockObject
     */
    protected $_urlEncoderMock;

    /**
     * @var Store|MockObject
     */
    protected $_storeMock;

    /**
     * @var Filesystem
     */
    protected $_object;

    protected function setUp(): void
    {
        $this->_contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->setMethods(['getRequest', 'getUrlEncoder'])
            ->getMock();

        $this->_storeManagerMock = $this->getMockBuilder(StoreManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['getStore'])
            ->getMock();

        $this->_imageHelperMock = $this->getMockBuilder(Images::class)
            ->disableOriginalConstructor()
            ->setMethods(['isUsingStaticUrlsAllowed'])
            ->getMock();

        $this->_requestMock = $this->getMockBuilder(Http::class)
            ->disableOriginalConstructor()
            ->setMethods(['getParam'])
            ->getMock();

        $this->_urlEncoderMock = $this->getMockBuilder(Encoder::class)
            ->disableOriginalConstructor()
            ->setMethods(['encode'])
            ->getMock();

        $this->_storeMock = $this->getMockBuilder(Store::class)
            ->disableOriginalConstructor()
            ->setMethods(['getBaseUrl', 'getUrl'])
            ->getMock();

        $this->_contextMock->expects($this->once())
            ->method('getRequest')
            ->willReturn($this->_requestMock);

        $this->_contextMock->expects($this->once())
            ->method('getUrlEncoder')
            ->willReturn($this->_urlEncoderMock);

        $this->_object = new Filesystem(
            $this->_contextMock,
            $this->_storeManagerMock,
            $this->_imageHelperMock
        );
    }

    public function testGetCurrentPath(): void
    {
        $requestPath = $this->_object->idEncode('/test/path');
        $expectedPath = '/test/path';

        $this->_requestMock->expects($this->once())
            ->method('getParam')
            ->with($this->_object->getTreeNodeName())
            ->willReturn($requestPath);

        $this->assertEquals($expectedPath, $this->_object->getCurrentPath());
    }

    public function testGetCurrentPathEmpty(): void
    {
        $path = '/';

        $this->_requestMock->expects($this->once())
            ->method('getParam')
            ->with($this->_object->getTreeNodeName())
            ->willReturn('');

        $this->assertEquals($path, $this->_object->getCurrentPath());
    }

    public function testGetShortFilenameShort(): void
    {
        $filename = 'test';

        $this->assertEquals($filename, $this->_object->getShortFilename($filename));
    }

    public function testGetShortFilenameLong(): void
    {
        $filename = 'test';
        $expected = 'tes...';

        $this->assertEquals($expected, $this->_object->getShortFilename($filename, 3));
    }

    public function testGetImageHtmlDeclaration(): void
    {
        $filename = 'test.jpg';
        $baseUrl = 'https://test.de/media/';
        $asTag = true;

        $returnVal = sprintf('<img src="%s" alt="" />', $baseUrl.$filename);

        $this->_storeManagerMock->expects($this->once())
            ->method('getStore')
            ->willReturn($this->_storeMock);

        $this->_storeMock->expects($this->once())
            ->method('getBaseUrl')
            ->with(UrlInterface::URL_TYPE_MEDIA)
            ->willReturn($baseUrl);

        $this->_imageHelperMock->expects($this->once())
            ->method('isUsingStaticUrlsAllowed')
            ->willReturn(true);

        $this->assertEquals($returnVal, $this->_object->getImageHtmlDeclaration($filename, $asTag));
    }

    public function testGetImageHtmlDeclarationAsTag(): void
    {
        $filename = 'test.jpg';
        $baseUrl = 'https://test.de/media/';

        $directive = 'TEST';
        $url = 'https://test.de/test';

        $this->_storeManagerMock->expects($this->atLeast(1))
            ->method('getStore')
            ->willReturn($this->_storeMock);

        $this->_storeMock->expects($this->once())
            ->method('getBaseUrl')
            ->with(UrlInterface::URL_TYPE_MEDIA)
            ->willReturn($baseUrl);

        $this->_imageHelperMock->expects($this->once())
            ->method('isUsingStaticUrlsAllowed')
            ->willReturn(false);

        $this->_urlEncoderMock->expects($this->once())
            ->method('encode')
            ->with($this->isType('string'))
            ->willReturn($directive);

        $this->_storeMock->expects($this->once())
            ->method('getUrl')
            ->with('cms/wysiwyg/directive',
                [
                    '___directive' => $directive,
                    '_escape_params' => false,
                ])
            ->willReturn($url);

        $this->assertEquals($url, $this->_object->getImageHtmlDeclaration($filename));
    }

    public function testGetImageHtmlDeclarationWithStaticUrls(): void
    {
        $filename = 'test.jpg';
        $baseUrl = 'https://test.de/media/';

        $this->_storeManagerMock->expects($this->atLeast(1))
            ->method('getStore')
            ->willReturn($this->_storeMock);

        $this->_storeMock->expects($this->once())
            ->method('getBaseUrl')
            ->with(UrlInterface::URL_TYPE_MEDIA)
            ->willReturn($baseUrl);

        $this->_imageHelperMock->expects($this->once())
            ->method('isUsingStaticUrlsAllowed')
            ->willReturn(true);

        $this->assertEquals($baseUrl.$filename, $this->_object->getImageHtmlDeclaration($filename));
    }
}