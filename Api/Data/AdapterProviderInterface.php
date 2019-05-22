<?php
namespace Flagbit\Flysystem\Api\Data;

/**
 * Interface AdapterConfigInterface
 * @package Flagbit\Flysystem\Api\Data
 */
interface AdapterProviderInterface
{
    const ADAPTER_ID = 'adapter_id';
    const NAME = 'name';
    const TYPE = 'type';
    const CONFIG_JSON = 'config_json';
    const CREATED_TIME = 'created_time';
    const UPDATED_TIME = 'updated_time';

    /**
     * @return int|null
     */
    public function getId();

    /**
     * @return string|null
     */
    public function getName();

    /**
     * @return string|null
     */
    public function getType();

    /**
     * @return string|null
     */
    public function getConfigJson();

    /**
     * @return array|null
     */
    public function getConfig();

    /**
     * @return string|null
     */
    public function getCreatedTime();

    /**
     * @return string|null
     */
    public function getUpdatedTime();

    /**
     * @param int $id
     * @return AdapterProviderInterface
     */
    public function setId($id);

    /**
     * @param string $name
     * @return AdapterProviderInterface
     */
    public function setName($name);

    /**
     * @param string $type
     * @return AdapterProviderInterface
     */
    public function setType($type);

    /**
     * @param string $configJson
     * @return AdapterProviderInterface
     */
    public function setConfigJson($configJson);

    /**
     * @param array $config
     * @return AdapterProviderInterface
     */
    public function setConfig($config);

    /**
     * @param int|string $date
     * @return AdapterProviderInterface
     */
    public function setCreatedTime($date);

    /**
     * @param int|string $date
     * @return AdapterProviderInterface
     */
    public function setUpdatedTime($date);
}
