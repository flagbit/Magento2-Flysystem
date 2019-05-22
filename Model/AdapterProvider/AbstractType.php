<?php
namespace Flagbit\Flysystem\Model\AdapterProvider;

use \Flagbit\Flysystem\Adapter\FilesystemAdapter;
use \Magento\Framework\Serialize\Serializer\Json;

class AbstractType
{
    protected $_json;

    /**
     * @var array
     */
    protected $_config = [];

    protected $_configFields = [];

    /**
     * AbstractType constructor.
     * @param Json $json
     * @param null $config
     */
    public function __construct(
        Json $json,
        $config = null
    ) {
        $this->_json = $json;
        $this->_config = $this->decodeConfig($config);
    }

    /**
     * @param $config
     * @return array
     */
    public function decodeConfig($config): array
    {
        $result = [];
        if(!is_array($config)) {
            $result = $this->_json->unserialize($config);
        }

        return $result;
    }

    /**
     * @param $config
     * @return string
     */
    public function encodeConfig($config): string
    {
        return $this->_json->serialize($config);
    }

    /**
     * @return string
     */
    public function getJsonConfig(): string
    {
        return $this->encodeConfig($this->_config);
    }

    /**
     * @return FilesystemAdapter|null
     */
    public function createAdapter(): ?FilesystemAdapter
    {
        return null;
    }
}
