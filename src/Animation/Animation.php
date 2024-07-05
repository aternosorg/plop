<?php

namespace Aternos\Plop\Animation;

use Aternos\Plop\Structure\Elements\Block;
use Aternos\Plop\Structure\Elements\ElementCommandList;

abstract class Animation
{
    /**
     * @param Block $block
     * @param string $prefix
     * @return ElementCommandList
     */
    abstract public function getBlockCommands(Block $block, string $prefix): ElementCommandList;
}
