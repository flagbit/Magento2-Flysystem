<?php
namespace Flagbit\Flysystem\Model;

use Flagbit\Flysystem\Api\AdapterProviderRepositoryInterface;
use Flagbit\Flysystem\Api\Data\AdapterProviderInterface;
use Flagbit\Flysystem\Model\ResourceModel\AdapterProvider as AdapterProviderResource;
use Magento\Framework\Exception;

/**
 * Class AdapterProviderRepository
 * @package Flagbit\Flysystem\Model
 */
class AdapterProviderRepository implements AdapterProviderRepositoryInterface
{
    const ERROR_SAVE = 'Could not save adapter provider %s';
    const ERROR_GET = 'Could not read adapter provider with id %s';

    /**
     * @var AdapterProviderFactory
     */
    protected $adapterProviderFactory;

    /**
     * @var AdapterProviderResource
     */
    protected $adapterProviderResource;

    /**
     * AdapterProviderRepository constructor.
     * @param AdapterProviderFactory $adapterProviderFactory
     * @param AdapterProviderResource $adapterProviderResource
     */
    public function __construct(
        AdapterProviderFactory $adapterProviderFactory,
        AdapterProviderResource $adapterProviderResource
    ) {
        $this->adapterProviderFactory = $adapterProviderFactory;
        $this->adapterProviderResource = $adapterProviderResource;
    }

    /**
     * Create adapter provider
     *
     * @param AdapterProviderInterface $adapterProvider
     *
     * @return AdapterProviderInterface
     * @throws Exception\CouldNotSaveException
     */
    public function save(AdapterProviderInterface $adapterProvider)
    {
        try {
            /** @var AdapterProvider $adapterProvider */
            $this->adapterProviderResource->save($adapterProvider);
        } catch (\Exception $e) {
            throw new Exception\CouldNotSaveException(__(self::ERROR_SAVE, $e->getMessage()), $e);
        }

        return $adapterProvider;
    }

    /**
     * Get adapter provider by adapter provider Id
     *
     * @param int $adapterProviderId
     *
     * @return AdapterProviderInterface
     * @throws Exception\NoSuchEntityException
     */
    public function get($adapterProviderId)
    {
        $adapterProvider = $this->adapterProviderFactory->create();
        $this->adapterProviderResource->load($adapterProvider, $adapterProviderId);
        /** @var AdapterProvider $adapterProvider */
        if (!$adapterProvider->getId()) {
            throw new Exception\NoSuchEntityException(__(self::ERROR_GET, $adapterProviderId));
        }
        return $adapterProvider;
    }

    /**
     * Delete adapter provider
     *
     * @param AdapterProviderInterface $adapterProvider
     *
     * @return bool true on success
     * @throws Exception\CouldNotDeleteException
     */
    public function delete(AdapterProviderInterface $adapterProvider)
    {
        try {
            /** @var AdapterProvider $adapterProvider */
            $this->adapterProviderResource->delete($adapterProvider);
        } catch (\Exception $exception) {
            throw new Exception\CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * @param int $adapterProviderId
     *
     * @return bool true on success
     * @throws Exception\NoSuchEntityException
     * @throws Exception\CouldNotDeleteException
     */
    public function deleteById($adapterProviderId)
    {
        return $this->delete($this->get($adapterProviderId));
    }
}