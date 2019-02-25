<?php
namespace Flagbit\Flysystem\Test\Unit\Model\Pool\TestModifiers;

use \Flagbit\Flysystem\Model\Pool\ModifierInterface;

/**
 * Class TestFileModifier002
 * @package Flagbit\Flysystem\Test\Unit\Model\Pool\TestModifiers
 */
class TestFileModifier002 implements ModifierInterface
{
    /**
     * @param array $data
     * @return string|null
     */
    public function modifyFile(array $data): ?string
    {
        return null;
    }
}
