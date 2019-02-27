<?php
namespace Flagbit\Flysystem\Test\Unit\Model\Pool;

use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Flagbit\Flysystem\Model\Pool\FileModifierPool;
use \Flagbit\Flysystem\Model\Pool\ModifierFactory;
use \Flagbit\Flysystem\Test\Unit\Model\Pool\TestModifiers\TestFileModifier001;
use \Flagbit\Flysystem\Test\Unit\Model\Pool\TestModifiers\TestFileModifier002;
use \Flagbit\Flysystem\Test\Unit\Model\Pool\TestModifiers\TestFileModifier003;
use \Flagbit\Flysystem\Test\Unit\Model\Pool\TestModifiers\TestFileModifier004;
use \Magento\Framework\Exception\LocalizedException;
use \PHPUnit\Framework\MockObject\MockObject;

/**
 * Class FileModifierPoolTest
 * @package Flagbit\Flysystem\Test\Unit\Model\Pool
 */
class FileModifierPoolTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ModifierFactory|MockObject
     */
    protected $_modifierFactoryMock;

    /**
     * @var Manager|MockObject
     */
    protected $_flysystemManagerMock;

    /**
     * @var TestFileModifier001|MockObject
     */
    protected $_testFileModifierMock001;

    /**
     * @var TestFileModifier002|MockObject
     */
    protected $_testFileModifierMock002;

    /**
     * @var TestFileModifier003|MockObject
     */
    protected $_testFileModifierMock003;

    /**
     * @var TestFileModifier004|MockObject
     */
    protected $_testFileModifierMock004;

    /**
     * @var FileModifierPool
     */
    protected $_object;

    /**
     * @var string
     */
    private $modalIdentifier = 'test_scope';

    /**
     * @var array
     */
    private $modifiers = [
        [
            'sortOrder' => 10,
            'scope' => 'test_scope',
            'class' => TestFileModifier001::class
        ],
        [
            'sortOrder' => 10,
            'scope' => 'invalid_scope',
            'class' => TestFileModifier002::class
        ],
        [
            'sortOrder' => 5,
            'scope' => ['test_scope', 'invalid_scope'],
            'class' => TestFileModifier003::class
        ],
        [
            'sortOrder' => 15,
            'scope' => ['invalid_scope', 'invalid_scope2'],
            'class' => TestFileModifier004::class
        ]
    ];

    private $filteredModifiers = [
        [
            'sortOrder' => 5,
            'scope' => ['test_scope', 'invalid_scope'],
            'class' => TestFileModifier003::class
        ],
        [
            'sortOrder' => 10,
            'scope' => 'test_scope',
            'class' => TestFileModifier001::class
        ]
    ];


    /**
     * @throws \ReflectionException
     */
    protected function setUp(): void
    {
        $this->_modifierFactoryMock = $this->getMockBuilder(ModifierFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->_flysystemManagerMock = $this->getMockBuilder(Manager::class)
            ->disableOriginalConstructor()
            ->setMethods(['getModalIdentifier'])
            ->getMock();

        $this->_testFileModifierMock001 = $this->getMockBuilder(TestFileModifier001::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_testFileModifierMock002 = $this->getMockBuilder(TestFileModifier002::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_testFileModifierMock003 = $this->getMockBuilder(TestFileModifier003::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_testFileModifierMock004 = $this->getMockBuilder(TestFileModifier004::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_flysystemManagerMock->expects($this->any())
            ->method('getModalIdentifier')
            ->willReturn($this->modalIdentifier);

        $this->_object = new FileModifierPool(
            $this->_modifierFactoryMock,
            $this->_flysystemManagerMock,
            $this->modifiers
        );
    }

    public function testGetModifiers(): void
    {
        $this->assertEquals($this->filteredModifiers, $this->_object->getModifiers());
    }

    public function testGetModifierInstances(): void
    {
        $expectedResult = [
            TestFileModifier003::class => $this->_testFileModifierMock003,
            TestFileModifier001::class => $this->_testFileModifierMock001
        ];

        $this->_modifierFactoryMock->expects($this->at(0))
            ->method('create')
            ->with(TestFileModifier003::class)
            ->willReturn($this->_testFileModifierMock003);

        $this->_modifierFactoryMock->expects($this->at(1))
            ->method('create')
            ->with(TestFileModifier001::class)
            ->willReturn($this->_testFileModifierMock001);

        $this->assertEquals($expectedResult, $this->_object->getModifierInstances());

        $this->_modifierFactoryMock->expects($this->never())
            ->method('create');
        $this->assertEquals($expectedResult, $this->_object->getModifierInstances());
    }

    public function testGetModifierInstancesException(): void
    {
        $invalidModifiers = [
            'test001' => [
                'sortOrder' => 10,
                'scope' => 'test_scope'
            ]
        ];

        $this->_object = new FileModifierPool(
            $this->_modifierFactoryMock,
            $this->_flysystemManagerMock,
            $invalidModifiers
        );

        $this->expectException(LocalizedException::class);
        $this->_object->getModifierInstances();
    }
}
