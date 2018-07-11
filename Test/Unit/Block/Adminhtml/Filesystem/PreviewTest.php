<?php
namespace Flagbit\Flysystem\Test\Unit\Block\Adminhtml\Filesystem;

use \Flagbit\Flysystem\Block\Adminhtml\Filesystem\Preview;
use \Magento\Backend\Block\Template\Context;
use \Magento\Backend\Model\Url;
use \PHPUnit\Framework\MockObject\MockObject;
use \PHPUnit\Framework\TestCase;

class PreviewTest extends TestCase
{
    /**
     * @var Context|MockObject
     */
    protected $_contextMock;

    /**
     * @var Url|MockObject
     */
    protected $_urlBuilderMock;

    /**
     * @var Preview
     */
    protected $_object;

    public function setUp()
    {
        $this->_contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->setMethods(['getUrlBuilder'])
            ->getMock();

        $this->_urlBuilderMock = $this->getMockBuilder(Url::class)
            ->disableOriginalConstructor()
            ->setMethods(['getUrl'])
            ->getMock();

        $this->_contextMock->expects($this->once())
            ->method('getUrlBuilder')
            ->will($this->returnValue($this->_urlBuilderMock));

        $this->_object = new Preview(
            $this->_contextMock
        );
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