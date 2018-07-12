<?php
namespace Flagbit\Flysystem\Test\Unit\Controller\Filemanager;

use \Flagbit\Flysystem\Adapter\FilesystemAdapter;
use \Flagbit\Flysystem\Controller\Adminhtml\Filemanager\Index;
use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Magento\Backend\App\Action\Context;
use \Magento\Backend\Model\Session;
use \Magento\Backend\Model\View\Result\Page;
use \Magento\Backend\Model\View\Result\PageFactory;
use \Magento\Framework\View\Page\Config;
use \Magento\Framework\View\Page\Title;
use \PHPUnit\Framework\MockObject\MockObject;
use \PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
{
    /**
     * @var Context|MockObject
     */
    protected $_contextMock;

    /**
     * @var Manager|MockObject
     */
    protected $_flysystemManagerMock;

    /**
     * @var Session|MockObject
     */
    protected $_sessionMock;

    /**
     * @var PageFactory|MockObject
     */
    protected $_resultPageFactoryMock;

    /**
     * @var Page|MockObject
     */
    protected $_resultPageMock;

    /**
     * @var FilesystemAdapter|MockObject
     */
    protected $_flysystemAdapterMock;

    /**
     * @var Config|MockObject
     */
    protected $_pageConfigMock;

    /**
     * @var Title|MockObject
     */
    protected $_pageTitleMock;

    /**
     * @var Index
     */
    protected $_object;

    public function setUp()
    {
        $this->_contextMock  = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_flysystemManagerMock = $this->getMockBuilder(Manager::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAdapter', 'setModalIdentifier'])
            ->getMock();

        $this->_sessionMock = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_resultPageFactoryMock = $this->getMockBuilder(PageFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->_resultPageMock = $this->getMockBuilder(Page::class)
            ->disableOriginalConstructor()
            ->setMethods(['addDefaultHandle', 'setActiveMenu', 'addBreadcrumb', 'getConfig'])
            ->getMock();

        $this->_flysystemAdapterMock = $this->getMockBuilder(FilesystemAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_pageConfigMock = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->setMethods(['getTitle'])
            ->getMock();

        $this->_pageTitleMock = $this->getMockBuilder(Title::class)
            ->disableOriginalConstructor()
            ->setMethods(['prepend'])
            ->getMock();

        $this->_object = new Index(
            $this->_contextMock,
            $this->_flysystemManagerMock,
            $this->_sessionMock,
            $this->_resultPageFactoryMock
        );
    }

    public function testExecute()
    {
        $this->_flysystemManagerMock->expects($this->exactly(2))
            ->method('getAdapter')
            ->willReturn($this->_flysystemAdapterMock);

        $this->_flysystemManagerMock->expects($this->once())
            ->method('setModalIdentifier')
            ->with($this->isType('string'));

        $this->_resultPageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->_resultPageMock);

        $this->_resultPageMock->expects($this->once())
            ->method('addDefaultHandle')
            ->willReturn($this->_resultPageMock);

        $this->_resultPageMock->expects($this->once())
            ->method('setActiveMenu')
            ->with($this->isType('string'))
            ->willReturn($this->_resultPageMock);

        $this->_resultPageMock->expects($this->once())
            ->method('addBreadcrumb')
            ->willReturn($this->_resultPageMock);

        $this->_resultPageMock->expects($this->once())
            ->method('getConfig')
            ->willReturn($this->_pageConfigMock);

        $this->_pageConfigMock->expects($this->once())
            ->method('getTitle')
            ->willReturn($this->_pageTitleMock);

        $this->_pageTitleMock->expects($this->once())
            ->method('prepend');

        $this->_object->execute();
    }

    public function testExecuteException()
    {
        $exception = new \Exception('test');

        $this->_flysystemManagerMock->expects($this->once())
            ->method('getAdapter')
            ->willThrowException($exception);

        $this->_resultPageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->_resultPageMock);

        $this->_resultPageMock->expects($this->once())
            ->method('addDefaultHandle')
            ->willReturn($this->_resultPageMock);

        $this->_resultPageMock->expects($this->once())
            ->method('setActiveMenu')
            ->with($this->isType('string'))
            ->willReturn($this->_resultPageMock);

        $this->_resultPageMock->expects($this->once())
            ->method('addBreadcrumb')
            ->willReturn($this->_resultPageMock);

        $this->_resultPageMock->expects($this->once())
            ->method('getConfig')
            ->willReturn($this->_pageConfigMock);

        $this->_pageConfigMock->expects($this->once())
            ->method('getTitle')
            ->willReturn($this->_pageTitleMock);

        $this->_pageTitleMock->expects($this->once())
            ->method('prepend');

        $this->_object->execute();
    }
}