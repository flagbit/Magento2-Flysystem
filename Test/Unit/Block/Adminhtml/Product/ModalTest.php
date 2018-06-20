<?php
namespace Flagbit\Flysystem\Test\Unit\Block\Adminhtml\Product;

use \Flagbit\Flysystem\Block\Adminhtml\Product\Modal;
use \Magento\Backend\Block\Template\Context;
use \PHPUnit\Framework\MockObject\MockObject;
use \PHPUnit\Framework\TestCase;

class ModalTest extends TestCase
{
    /**
     * @var Context|MockObject
     */
    protected $_contextMock;

    /**
     * @var Modal
     */
    protected $_object;

    public function setUp()
    {
        $this->_contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testConstructor()
    {
        $this->_object = new Modal(
            $this->_contextMock
        );

        $this->assertEquals($this->_object->getTemplate(), 'Flagbit_Flysystem::product/form/modal.phtml');
    }
}