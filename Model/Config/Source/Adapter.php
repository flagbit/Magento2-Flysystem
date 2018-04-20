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
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var array
     */
    protected $adapters = [];

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var FilesystemAdapterFactory
     */
    protected $flyFactory;

    /**
     * Adapter constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param FilesystemAdapterFactory $flyFactory
     * @param array $adapters
     * @param array $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        FilesystemAdapterFactory $flyFactory,
        array $adapters = [],
        array $data = []
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->adapters = $adapters;
        $this->flyFactory = $flyFactory;
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