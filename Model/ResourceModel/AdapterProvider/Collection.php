<?php
namespace Flagbit\Flysystem\Model\ResourceModel\AdapterProvider;

use \Flagbit\Flysystem\Model\AdapterProvider;
use \Flagbit\Flysystem\Model\ResourceModel\AdapterProvider as AdapterProviderResource;
use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'adapter_id';

    protected function _construct()
    {
        $this->_init(AdapterProvider::class, AdapterProviderResource::class);
        $this->_map['fields'][$this->_idFieldName] = 'main_table.' . $this->_idFieldName;
    }

    public function toOptionArray()
    {
        return $this->_toOptionArray($this->_idFieldName, 'name');
    }
}
