<?php
namespace Flagbit\Flysystem\Model\ResourceModel;

use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use \Magento\Framework\Model\ResourceModel\Db\Context;

class AdapterProvider extends AbstractDb
{
    /**
     * Adapter constructor.
     * @param Context $context
     * @param null $connectionName
     */
    public function __construct(
        Context $context,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
    }

    protected function _construct()
    {
        $this->_init('flagbit_flysystem_adapter', 'adapter_id');
    }
}
