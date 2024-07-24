<?php

namespace Aternos\Plop\Animation;

use Aternos\Plop\Output\TimedCommand;
use Aternos\Plop\Structure\Elements\Block;

abstract class Animation
{
    /**
     * @param Block $block
     * @param string $prefix
     * @return TimedCommand[]
     */
    abstract public function getBlockCommands(Block $block, string $prefix): array;
}
