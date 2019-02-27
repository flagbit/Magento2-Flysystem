<?php
namespace Flagbit\Flysystem\Model\Pool;

use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Magento\Framework\Exception\LocalizedException;

/**
 * Class FileModifierPool
 * Is used as type for virtualTypes in di.xml to write extensions for Flysystem controllers
 * @package Flagbit\Flysystem\Model\Pool
 */
class FileModifierPool implements FileModifierPoolInterface
{
    /**
     * Modifier classes for Flysystem controller actions
     *
     * @var array
     */
    protected $modifiers = [];

    /**
     * @var array
     */
    protected $modifierInstances = [];

    /**
     * @var ModifierFactory
     */
    protected $factory;

    /**
     * @var Manager
     */
    protected $flysystemManager;


    /**
     * FileModifierPool constructor.
     * @param ModifierFactory $factory
     * @param Manager $flysystemManager
     * @param array $modifiers
     */
    public function __construct(
        ModifierFactory $factory,
        Manager $flysystemManager,
        array $modifiers = []
    ) {
        $this->factory = $factory;
        $this->flysystemManager = $flysystemManager;
        $this->modifiers = $this->sortAndFilter($modifiers);
    }

    /**
     * @return array
     */
    public function getModifiers(): array
    {
        return $this->modifiers;
    }

    /**
     * Retrieve modifiers instantiated
     *
     * @return ModifierInterface[]
     * @throws LocalizedException
     */
    public function getModifierInstances()
    {
        if ($this->modifierInstances) {
            return $this->modifierInstances;
        }

        foreach ($this->modifiers as $modifier) {
            if (!isset($modifier['class']) || empty($modifier['class'])) {
                throw new LocalizedException(__('The parameter "class" is missing. Set the "class" and try again.'));
            }

            $this->modifierInstances[$modifier['class']] = $this->factory->create($modifier['class']);
        }

        return $this->modifierInstances;
    }

    /**
     * Sorting modifiers according to sort order
     *
     * @param array $data
     * @return array
     */
    protected function sortAndFilter(array $data)
    {
        usort($data, function (array $a, array $b) {
            $a['sortOrder'] = $this->getSortOrder($a);
            $b['sortOrder'] = $this->getSortOrder($b);

            if ($a['sortOrder'] == $b['sortOrder']) {
                return 0;
            }

            return ($a['sortOrder'] < $b['sortOrder']) ? -1 : 1;
        });

        $filteredData = [];
        if($modalIdentifier = $this->flysystemManager->getModalIdentifier()) {
            foreach($data as $single) {
                if (
                    isset($single['scope']) &&
                    (!is_array($single['scope']) && $single['scope'] === $modalIdentifier ||
                        is_array($single['scope']) && in_array($modalIdentifier, $single['scope']))
                ) {
                    array_push($filteredData, $single);
                }
            }
        }
        $data = $filteredData;
        return $data;
    }

    /**
     * Retrieve sort order from array
     *
     * @param array $variable
     * @return int
     */
    protected function getSortOrder(array $variable)
    {
        return (isset($variable['sortOrder']) && !empty($variable['sortOrder'])) ? $variable['sortOrder'] : 0;
    }
}
