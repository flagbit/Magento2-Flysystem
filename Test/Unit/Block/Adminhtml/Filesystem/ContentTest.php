<?php
namespace Flagbit\Flysystem\Test\Unit\Block\Adminhtml\Filesystem;

use \Flagbit\Flysystem\Block\Adminhtml\Filesystem\Content;
use \Magento\Backend\Block\Widget\Button\ButtonList;
use \Magento\Backend\Block\Widget\Context;
use \Magento\Backend\Model\Url;
use \Magento\Framework\App\Request\Http;
use \Magento\Framework\Authorization;
use \Magento\Framework\Serialize\Serializer\Json;
use \PHPUnit\Framework\MockObject\MockObject;
use \PHPUnit\Framework\TestCase;

class ContentTest extends TestCase
{
    /**
     * @var Context|MockObject
     */
    protected $_contextMock;

    /**
     * @var Json|MockObject
     */
    protected $_jsonEncoderMock;

    /**
     * @var Authorization|MockObject
     */
    protected $_authorizationMock;

    /**
     * @var ButtonList|MockObject
     */
    protected $_buttonListMock;

    /**
     * @var Http|MockObject
     */
    protected $_requestHttpMock;

    /**
     * @var Url|MockObject
     */
    protected $_urlBuilderMock;

    /**
     * @var Content
     */
    protected $_object;

    public function setUp()
    {
        $this->_contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->setMethods(['getButtonList', 'getRequest', 'getUrlBuilder', 'getAuthorization'])
            ->getMock();

        $this->_jsonEncoderMock = $this->getMockBuilder(Json::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_authorizationMock = $this->getMockBuilder(Authorization::class)
            ->disableOriginalConstructor()
            ->setMethods(['isAllowed'])
            ->getMock();

        $this->_buttonListMock = $this->getMockBuilder(ButtonList::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_requestHttpMock = $this->getMockBuilder(Http::class)
            ->disableOriginalConstructor()
            ->setMethods(['getParam'])
            ->getMock();

        $this->_urlBuilderMock = $this->getMockBuilder(Url::class)
            ->disableOriginalConstructor()
            ->setMethods(['getUrl'])
            ->getMock();

        $this->_contextMock->expects($this->once())
            ->method('getButtonList')
            ->willReturn($this->_buttonListMock);

        $this->_contextMock->expects($this->once())
            ->method('getRequest')
            ->willReturn($this->_requestHttpMock);

        $this->_contextMock->expects($this->once())
            ->method('getUrlBuilder')
            ->willReturn($this->_urlBuilderMock);

        $this->_contextMock->expects($this->once())
            ->method('getAuthorization')
            ->willReturn($this->_authorizationMock);

        $this->_buttonListMock->expects($this->exactly(2))
            ->method('remove')
            ->with($this->isType('string'));

        $this->_authorizationMock->expects($this->exactly(4))
            ->method('isAllowed')
            ->with($this->isType('string'))
            ->willReturn(true);

        $this->_buttonListMock->expects($this->exactly(5))
            ->method('add')
            ->with($this->isType('string'), $this->isType('array'), 0, 0, 'header');

        $this->_object = new Content(
            $this->_contextMock,
            $this->_jsonEncoderMock
        );
    }

    public function testGetFileBrowserSetupObject()
    {
        $elementId = 'element_id';
        $jsonString = 'jsonString';

        $this->_requestHttpMock->expects($this->once())
            ->method('getParam')
            ->with('target_element_id')
            ->willReturn($elementId);

        $this->_urlBuilderMock->expects($this->exactly(5))
            ->method('getUrl')
            ->withAnyParameters()
            ->willReturn($this->isType('string'));

        $this->_jsonEncoderMock->expects($this->once())
            ->method('serialize')
            ->with($this->isType('array'))
            ->willReturn($jsonString);

        $this->assertEquals($jsonString, $this->_object->getFilebrowserSetupObject());
    }

    public function testGetModalIdentifier()
    {
        $identifier = 'modal_id';

        $this->_requestHttpMock->expects($this->once())
            ->method('getParam')
            ->with('identifier')
            ->willReturn($identifier);

        $this->assertEquals($identifier, $this->_object->getModalIdentifier());
    }

    public function testGetPreviewUrl()
    {
        $url = 'test.com/preview';

        $this->_urlBuilderMock->expects($this->once())
            ->method('getUrl')
            ->with($this->isType('string'))
            ->willReturn($url);

        $this->assertEquals($url, $this->_object->getPreviewUrl());
    }
}