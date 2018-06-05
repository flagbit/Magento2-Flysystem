<?php
namespace Flagbit\Flysystem\Test\Unit\Model\Config\Source;

use \Flagbit\Flysystem\Model\Config\Source\Adapter;

class AdapterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Adapter
     */
    protected $adapter;

    /**
     * Setup test cases
     *
     * @return void
     */
    public function setUp()
    {
        $testAdapters = [
            ['identifier' => 'testKey1', 'title' => 'testValue1'],
            ['identifier' => 'testKey2', 'title' => 'testValue2']
        ];

        $this->adapter = new Adapter($testAdapters);
    }

    /**
     * Run Test for testTopOptionArray
     *
     * @return void
     */
    public function testToOptionArray()
    {
        $expectedReturn = [
            null => 'Magento2 Default',
            'testKey1' => 'testValue1',
            'testKey2' => 'testValue2'
        ];

        $this->assertEquals($expectedReturn, $this->adapter->toOptionArray());
    }
}