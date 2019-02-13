<?php
namespace Flagbit\Flysystem\Test\Unit\Model\Config\Source;

use \Flagbit\Flysystem\Model\Config\Source\Adapter;

/**
 * Class AdapterTest
 * @package Flagbit\Flysystem\Test\Unit\Model\Config\Source
 */
class AdapterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Adapter
     */
    protected $_adapter;

    /**
     * Setup test cases
     *
     * @return void
     */
    protected function setUp(): void
    {
        $testAdapters = [
            ['identifier' => 'testKey1', 'title' => 'testValue1'],
            ['identifier' => 'testKey2', 'title' => 'testValue2']
        ];

        $this->_adapter = new Adapter($testAdapters);
    }

    /**
     * Run Test for testTopOptionArray
     *
     * @return void
     */
    public function testToOptionArray(): void
    {
        $expectedReturn = [
            null => 'Use no Flysystem Adapter',
            'testKey1' => 'testValue1',
            'testKey2' => 'testValue2'
        ];

        $this->assertEquals($expectedReturn, $this->_adapter->toOptionArray());
    }
}