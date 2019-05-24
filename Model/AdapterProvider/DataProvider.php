<?php
namespace Flagbit\Flysystem\Model\AdapterProvider;

use Flagbit\Flysystem\Model\ResourceModel\AdapterProvider\Collection;
use Flagbit\Flysystem\Model\ResourceModel\AdapterProvider\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use \Magento\Ui\DataProvider\AbstractDataProvider;

/**
 * Class DataProvider
 * @package Flagbit\Flysystem\Model\AdapterProvider
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * DataProvider constructor.
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param CollectionFactory $adapterCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $adapterCollectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $adapterCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Prepares Meta
     *
     * @param array $meta
     * @return array
     */
    public function getMeta()
    {
        var_dump($this->meta);
        var_dump($this->data);

        return $this->meta;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var $adapter \Flagbit\Flysystem\Model\AdapterProvider */
        foreach ($items as $adapter) {
            $this->loadedData[$adapter->getId()] = $adapter->getData();
        }

        $data = $this->dataPersistor->get('flagbit_flysystem_adapter');
        if (!empty($data)) {
            $adapter = $this->collection->getNewEmptyItem();
            $adapter->setData($data);
            $this->loadedData[$adapter->getId()] = $adapter->getData();
            $this->dataPersistor->clear('flagbit_flysystem_adapter');
        }

        return $this->loadedData;
    }
}
