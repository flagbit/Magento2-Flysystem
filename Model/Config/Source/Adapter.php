<?php
namespace Flagbit\Flysystem\Model\Config\Source;

use \Flagbit\Flysystem\Adapter\FilesystemAdapterFactory;
use \Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class Adapter
 * @package Flagbit\Flysystem\Model\Config\Source
 */
class Adapter extends \Magento\Framework\DataObject implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var array
     */
    protected $adapters = [];

    /**
     * @var array
     */
    protected $options = [];

    /**
     * Adapter constructor.
     * @param array $adapters
     * @param array $data
     */
    public function __construct(
        array $adapters = [],
        array $data = []
    ) {
        $this->adapters = $adapters;
        parent::__construct($data);
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [null => 'Magento2 Default'];

        foreach($this->adapters as $adapter) {
            $options[$adapter['identifier']] = $adapter['title'];
        }

        return $options;
    }

}