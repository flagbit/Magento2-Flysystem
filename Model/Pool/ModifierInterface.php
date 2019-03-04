<?php
namespace Flagbit\Flysystem\Model\Pool;

interface ModifierInterface
{
    /**
     * @param array $data
     * @return string|null
     */
    public function modifyFile(array $data);
}
