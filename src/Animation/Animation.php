<?php

namespace Aternos\Plop\Animation;

use Aternos\Plop\Structure\Elements\Block;

abstract class Animation
{
    /**
     * @param Block $block
     * @param string $startIf
     * @param string $prefix
     * @return string[]
     */
    abstract public function getBlockCommands(Block $block, string $startIf, string $prefix): array;
}
