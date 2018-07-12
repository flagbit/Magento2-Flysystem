<?php
namespace Flagbit\Flysystem\Model\Config\Source;

/**
 * Class Adapter
 * @package Flagbit\Flysystem\Model\Config\Source
 */
class Adapter extends \Magento\Framework\DataObject implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var array
     */
    protected $_adapters = [];

    /**
     * @var array
     */
    protected $_options = [];

    /**
     * Adapter constructor.
     * @param array $adapters
     * @param array $data
     */
    public function __construct(
        array $adapters = [],
        array $data = []
    ) {
        $this->_adapters = $adapters;
        parent::__construct($data);
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        if(count($this->_options) === 0) {
            $options = [null => 'Use no Flysystem Adapter'];

            foreach ($this->_adapters as $adapter) {
                $options[$adapter['identifier']] = $adapter['title'];
            }

            $this->_options = $options;
        }

        return $this->_options;
    }

}