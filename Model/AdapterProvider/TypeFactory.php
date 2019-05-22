<?php
namespace Flagbit\Flysystem\Model\AdapterProvider;

use \Magento\Framework\ObjectManagerInterface;

/**
 * Class TypeFactory
 * @package Flagbit\Flysystem\Model\Pool
 */
class TypeFactory
{
    /**
     * Object Manager
     *
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * Construct
     *
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Create model
     *
     * @param string $className
     * @param array $data
     * @return AbstractType
     * @throws \InvalidArgumentException
     */
    public function create($className, array $data = [])
    {
        $model = $this->objectManager->create($className, $data);

        if (!$model instanceof AbstractType) {
            throw new \InvalidArgumentException(
                'Type "' . $className . '" is not instance on ' . AbstractType::class
            );
        }

        return $model;
    }
}
