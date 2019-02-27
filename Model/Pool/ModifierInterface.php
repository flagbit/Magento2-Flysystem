<?php
namespace Flagbit\Flysystem\Model\Pool;

interface ModifierInterface
{
    /**
     * @param array $data
     * @return string
     */
    public function modifyFile(array $data): ?string;
}
