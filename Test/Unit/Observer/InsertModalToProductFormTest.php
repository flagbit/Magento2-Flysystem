<?php
namespace Flagbit\Flysystem\Test\Unit\Observer;

use \Flagbit\Flysystem\Block\Adminhtml\Product\Modal;
use \Flagbit\Flysystem\Observer\InsertModalToProductForm;
use \Magento\Catalog\Block\Adminhtml\Product\Helper\Form\Gallery\Content;
use \Magento\Framework\Event\Observer;
use \Magento\Framework\Logger\Monolog;
use \PHPUnit\Framework\MockObject\MockObject;
use \PHPUnit\Framework\TestCase;

class InsertModalToProductFormTest extends TestCase
{
    /**
     * @var Monolog|MockObject
     */
    protected $_loggerMock;

    /**
     * @var Content|MockObject
     */
    protected $_blockMock;

    /**
     * @var Observer|MockObject
     */
    protected $_observerMock;

    /**
     * @var InsertModalToProductForm
     */
    protected $_object;

    protected function setUp(): void
    {
        $this->_loggerMock = $this->getMockBuilder(Monolog::class)
            ->disableOriginalConstructor()
            ->setMethods(['critical'])
            ->getMock();

        $this->_blockMock = $this->getMockBuilder(Content::class)
            ->disableOriginalConstructor()
            ->setMethods(['setTemplate', 'addChild'])
            ->getMock();

        $this->_observerMock = $this->getMockBuilder(Observer::class)
            ->disableOriginalConstructor()
            ->setMethods(['getBlock'])
            ->getMock();

        $this->_object = new InsertModalToProductForm(
            $this->_loggerMock
        );
    }

    public function testExecute(): void
    {
        $template = 'Flagbit_Flysystem::/product/form/gallery.phtml';
        $block_id = 'flysystem-modal';

        $this->_observerMock->expects($this->exactly(2))
            ->method('getBlock')
            ->willReturn($this->_blockMock);

        $this->_blockMock->expects($this->once())
            ->method('setTemplate')
            ->with($template);

        $this->_blockMock->expects($this->once())
            ->method('addChild')
            ->with($block_id, Modal::class);

        $this->_object->execute($this->_observerMock);
    }

    public function testExecuteException(): void
    {
        $exception = new \Exception();

        $this->_observerMock->expects($this->once())
            ->method('getBlock')
            ->willThrowException($exception);

        $this->_loggerMock->expects($this->once())
            ->method('critical')
            ->with($exception->getMessage());

        $this->_object->execute($this->_observerMock);
    }
}