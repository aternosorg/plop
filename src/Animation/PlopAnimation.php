<?php

namespace Aternos\Plop\Animation;

use Aternos\Plop\Structure\Elements\Block;

class PlopAnimation extends BlockDisplayAnimation
{
    /**
     * @inheritDoc
     */
    protected function getInitialTransform(Block $block): string
    {
        return '{left_rotation:[0f,0f,0f,1f],right_rotation:[0f,0f,0f,1f],translation:[0.45f,0.45f,0.45f],scale:[0.0f,0.0f,0.0f]}';
    }
}
