<?php
namespace Flagbit\Flysystem\Model\Pool;

/**
 * Interface FileModifierPoolInterface
 * @package Flagbit\Flysystem\Model\Pool
 */
interface FileModifierPoolInterface
{
    /**
     * @return array
     */
    public function getModifiers(): array;

    /**
     * @return ModifierInterface[]
     */
    public function getModifierInstances();
}
