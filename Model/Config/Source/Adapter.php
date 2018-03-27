<?php
namespace Flagbit\Flysystem\Model\Config\Source;

use Flagbit\Flysystem\Adapter\FilesystemAdapterFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Adapter extends \Magento\Framework\DataObject implements \Magento\Framework\Option\ArrayInterface
{
    protected $scopeConfig;

    protected $adapters = [];

    protected $options = [];

    protected $flyFactory;

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

    public function toOptionArray()
    {
        $options = [null => 'Magento2 Default'];

        foreach($this->adapters as $adapter) {
            $options[$adapter['identifier']] = $adapter['title'];
        }

        return $options;
    }

}