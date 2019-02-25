<?php
namespace Flagbit\Flysystem\Test\Unit\Model\Pool;

use \Flagbit\Flysystem\Model\Pool\ModifierFactory;
use \Flagbit\Flysystem\Test\Unit\Model\Pool\TestModifiers\TestFileModifier001;
use \Magento\Framework\App\Helper\AbstractHelper;
use \Magento\Framework\App\ObjectManager;
use \PHPUnit\Framework\MockObject\MockObject;

/**
 * Class ModifierFactoryTest
 * @package Flagbit\Flysystem\Test\Unit\Model\Pool
 */
class ModifierFactoryTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var ObjectManager|MockObject
     */
    protected $_objectManagerMock;

    /**
     * @var TestFileModifier001|MockObject
     */
    protected $_fileModifierMock;

    /**
     * @var AbstractHelper|MockObject
     */
    protected $_abstractHelperMock;

    /**
     * @var ModifierFactory
     */
    protected $_object;


    /**
     * @throws \ReflectionException
     */
    protected function setUp(): void
    {
        $this->_objectManagerMock = $this->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->_fileModifierMock = $this->getMockBuilder(TestFileModifier001::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_abstractHelperMock = $this->getMockBuilder(AbstractHelper::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_object = new ModifierFactory(
            $this->_objectManagerMock
        );
    }

    public function testCreate(): void
    {
        $className = 'Flagbit\Flysystem\Model\Pool\Modifier\CmsWysiwygImage';
        $data = ['modifiers' => []];

        $this->_objectManagerMock->expects($this->once())
            ->method('create')
            ->with($className, $data)
            ->willReturn($this->_fileModifierMock);

        $this->assertEquals($this->_fileModifierMock, $this->_object->create($className, $data));
    }

    public function testCreateException(): void
    {
        $className = 'Magento\Framework\App\Helper\AbstractHelper';
        $data = ['modifiers' => []];

        $this->_objectManagerMock->expects($this->once())
            ->method('create')
            ->with($className, $data)
            ->willReturn($this->_abstractHelperMock);

        $this->expectException(\InvalidArgumentException::class);
        $this->_object->create($className, $data);
    }
}
