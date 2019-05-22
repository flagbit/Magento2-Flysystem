<?php
namespace Flagbit\Flysystem\Model;

use \Flagbit\Flysystem\Adapter\FilesystemAdapter;
use \Flagbit\Flysystem\Api\Data\AdapterProviderInterface;
use \Flagbit\Flysystem\Helper\Config;
use \Flagbit\Flysystem\Helper\Errors;
use \Flagbit\Flysystem\Model\AdapterProvider\AbstractType;
use \Flagbit\Flysystem\Model\AdapterProvider\TypeFactory;
use \Flagbit\Flysystem\Model\ResourceModel\AdapterProvider as ResourceAdapterProvider;
use \Magento\Framework\Exception\LocalizedException;
use \Magento\Framework\Model\AbstractModel;
use \Magento\Framework\Model\Context;
use \Magento\Framework\Registry;

/**
 * Class AdapterConfig
 * @package Flagbit\Flysystem\Model\AdapterConfig
 */
class AdapterProvider extends AbstractModel implements AdapterProviderInterface
{
    /**
     * @var FilesystemAdapter
     */
    protected $_adapter;

    /**
     * @var string
     */
    protected $_path;

    /**
     * @var AbstractType
     */
    protected $_adapterType;

    /**
     * @var Config
     */
    protected $_configHelper;

    /**
     * @var TypeFactory
     */
    protected $_typeFactory;

    /**
     * AdapterProvider constructor.
     * @param Context $context
     * @param Registry $registry
     * @param Config $configHelper
     * @param TypeFactory $typeFactory
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Config $configHelper,
        TypeFactory $typeFactory
    ) {
        $this->_configHelper = $configHelper;
        $this->_typeFactory = $typeFactory;
        parent::__construct($context, $registry);
    }

    protected function _construct()
    {
        if ($type = $this->getType()) {
            $this->createTypeProvider();
        }
        $this->_init(ResourceAdapterProvider::class);
    }

    /**
     * @return AbstractModel|void
     */
    public function beforeSave()
    {
        parent::beforeSave();
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->getData(self::ADAPTER_ID);
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * @return string|null
     */
    public function getType()
    {
        return $this->getData(self::TYPE);
    }

    /**
     * @return string|null
     */
    public function getConfigJson()
    {
        return $this->getData(self::CONFIG_JSON);
    }

    /**
     * @return array|null
     */
    public function getConfig()
    {
        return null;
    }

    /**
     * @return string|null
     */
    public function getCreatedTime()
    {
        return $this->getData(self::CREATED_TIME);
    }

    /**
     * @return string|null
     */
    public function getUpdatedTime()
    {
        return $this->getData(self::UPDATED_TIME);
    }

    /**
     * @param string $name
     * @return AdapterProviderInterface
     */
    public function setName($name)
    {
        $this->setData(self::NAME, $name);
        return $this;
    }

    /**
     * @param string $type
     * @return AdapterProviderInterface
     */
    public function setType($type)
    {
        $this->setData(self::TYPE, $type);
        return $this;
    }

    /**
     * @param string $configJson
     * @return AdapterProviderInterface
     */
    public function setConfigJson($configJson)
    {
        $this->setData(self::CONFIG_JSON, $configJson);
        return $this;
    }

    /**
     * @param array $config
     * @return AdapterProviderInterface
     */
    public function setConfig($config)
    {
        return $this;
    }

    /**
     * @param int|string $date
     * @return AdapterProviderInterface|void
     */
    public function setCreatedTime($date)
    {
        $this->setData(self::CREATED_TIME, $date);
    }

    /**
     * @param int|string $date
     * @return AdapterProviderInterface|void
     */
    public function setUpdatedTime($date)
    {
        $this->setData(self::UPDATED_TIME, $date);
    }

    /**
     * @return $this
     */
    public function createTypeProvider()
    {
        if ($adapter = $this->_configHelper->getAdapter($this->getType())) {
            $this->_adapterType = $this->_typeFactory->create($adapter['class'], ['config' => $this->getConfigJson()]);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function createAdapter()
    {
        if ($this->createTypeProvider()) {
            if($this->_adapterType) {
                $this->_adapter = $this->_adapterType->createAdapter();
            }
        }
        return $this;
    }

    /**
     * @param bool $createIfNotExists
     * @return FilesystemAdapter
     * @throws LocalizedException
     */
    public function getAdapter(bool $createIfNotExists = true)
    {
        if (!$this->_adapter && $createIfNotExists) {
            $this->createAdapter();
        }

        if (!$this->_adapter) {
            throw new LocalizedException(Errors::getErrorMessage(111));
        }
        return $this->_adapter;
    }

    /**
     * @return string|null
     */
    public function getPath()
    {
        return $this->_path;
    }

    /**
     * @param string|null $path
     */
    public function setPath($path)
    {
        $this->_path = $path;
    }
}
