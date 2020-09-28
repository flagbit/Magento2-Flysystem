<?php
namespace Flagbit\Flysystem\Test\Unit\Block\Adminhtml\Filesystem\Content;

use \Flagbit\Flysystem\Block\Adminhtml\Filesystem\Content\Uploader;
use \Magento\Backend\Block\Template\Context;
use \Magento\Backend\Model\Url;
use \Magento\Framework\App\ObjectManager;
use \Magento\Framework\App\Request\Http;
use \Magento\Framework\Data\Form\FormKey;
use \Magento\Framework\File\Size;
use \Magento\Framework\Math\Random;
use \PHPUnit\Framework\MockObject\MockObject;
use \PHPUnit\Framework\TestCase;

class UploaderTest extends TestCase
{
    /**
     * @var Context|MockObject
     */
    protected $_contextMock;

    /**
     * @var Size|MockObject
     */
    protected $_fileSizeMock;

    /**
     * @var Url|MockObject
     */
    protected $_urlBuilderMock;

    /**
     * @var Random|MockObject
     */
    protected $_mathRandomMock;

    /**
     * @var Http|MockObject
     */
    protected $_requestHttpMock;

    /**
     * @var FormKey|MockObject
     */
    protected $_formKeyMock;

    /**
     * @var ObjectManager|MockObject
     */
    protected $_objectManagerMock;

    /**
     * @var Uploader
     */
    protected $_object;

    protected function setUp(): void
    {
        $this->_contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->setMethods(['getUrlBuilder', 'getMathRandom', 'getRequest', 'getFormKey'])
            ->getMock();

        $this->_fileSizeMock = $this->getMockBuilder(Size::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_urlBuilderMock = $this->getMockBuilder(Url::class)
            ->disableOriginalConstructor()
            ->setMethods(['getUrl'])
            ->getMock();

        $this->_mathRandomMock = $this->getMockBuilder(Random::class)
            ->disableOriginalConstructor()
            ->setMethods(['getUniqueHash'])
            ->getMock();

        $this->_requestHttpMock = $this->getMockBuilder(Http::class)
            ->disableOriginalConstructor()
            ->setMethods(['getParam'])
            ->getMock();

        $this->_formKeyMock = $this->getMockBuilder(FormKey::class)
            ->disableOriginalConstructor()
            ->setMethods(['getFormKey'])
            ->getMock();

        $this->_objectManagerMock = $this->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();

        ObjectManager::setInstance($this->_objectManagerMock);

        $this->_contextMock->expects($this->once())
            ->method('getUrlBuilder')
            ->willReturn($this->_urlBuilderMock);

        $this->_contextMock->expects($this->once())
            ->method('getMathRandom')
            ->willReturn($this->_mathRandomMock);

        $this->_contextMock->expects($this->once())
            ->method('getRequest')
            ->willReturn($this->_requestHttpMock);

        $this->_contextMock->expects($this->once())
            ->method('getFormKey')
            ->willReturn($this->_formKeyMock);
    }

    public function testContructor(): void
    {
        $uniqueHash = 'id_test';
        $uri = 'flagbit_flysystem/*/upload';
        $url = 'test.test/'.$uri;
        $mediaType = 'test';
        $formKey = 'test';

        $this->_mathRandomMock->expects($this->once())
            ->method('getUniqueHash')
            ->withAnyParameters()
            ->willReturn($uniqueHash);

        $this->_formKeyMock->expects($this->once())
            ->method('getFormKey')
            ->willReturn($formKey);

        $this->_requestHttpMock->expects($this->once())
            ->method('getParam')
            ->with('type')
            ->willReturn($mediaType);

        $this->_urlBuilderMock->expects($this->at(1))
            ->method('getUrl')
            ->with($uri, $this->arrayHasKey('type'))
            ->willReturn($url);

        $this->_object = new Uploader(
            $this->_contextMock,
            $this->_fileSizeMock
        );
    }

    public function testContructorWithMediaType(): void
    {
        $uniqueHash = 'id_test';
        $uri = 'flagbit_flysystem/*/upload';
        $url = 'test.test/'.$uri;
        $mediaType = 'test';
        $formKey = 'test';

        $this->_mathRandomMock->expects($this->once())
            ->method('getUniqueHash')
            ->withAnyParameters()
            ->willReturn($uniqueHash);

        $this->_formKeyMock->expects($this->once())
            ->method('getFormKey')
            ->willReturn($formKey);

        $this->_urlBuilderMock->expects($this->at(1))
            ->method('getUrl')
            ->with($uri, $this->arrayHasKey('type'))
            ->willReturn($url);

        $this->_object = new Uploader(
            $this->_contextMock,
            $this->_fileSizeMock,
            ['media_type' => $mediaType]
        );
    }
}
