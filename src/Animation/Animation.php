<?php

namespace Aternos\Plop\Animation;

use Aternos\Plop\Structure\Elements\Block;
use Aternos\Plop\Structure\Elements\ElementCommandList;

abstract class Animation
{
    /**
     * @param Block $block
     * @param string $prefix
     * @param int $tick
     * @return ElementCommandList
     */
    abstract public function getBlockCommands(Block $block, string $prefix, int $tick): ElementCommandList;
}
