<?php
namespace Flagbit\Flysystem\Api;

use \Flagbit\Flysystem\Api\Data\AdapterProviderInterface;
use \Magento\Framework\Exception;
/**
 * @api
 */
interface AdapterProviderRepositoryInterface
{
    /**
     * Create adapter provider
     *
     * @param AdapterProviderInterface $adapterProvider
     *
     * @throws Exception\CouldNotSaveException
     * @return AdapterProviderInterface
     */
    public function save(AdapterProviderInterface $adapterProvider);

    /**
     * Get menu by adapter provider Id
     *
     * @param int $adapterProviderId
     *
     * @throws Exception\NoSuchEntityException
     * @return AdapterProviderInterface
     */
    public function get($adapterProviderId);

    /**
     * Delete adapter provider
     *
     * @param AdapterProviderInterface $adapterProvider
     *
     * @throws Exception\CouldNotDeleteException
     * @return bool true on success
     */
    public function delete(AdapterProviderInterface $adapterProvider);

    /**
     * @param int $adapterProviderId
     *
     * @throws Exception\CouldNotDeleteException
     * @throws Exception\NoSuchEntityException
     * @return bool true on success
     */
    public function deleteById($adapterProviderId);
}
