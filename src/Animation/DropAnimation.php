<?php

namespace Aternos\Plop\Animation;

use Aternos\Plop\Structure\Elements\Block;

class DropAnimation extends BlockDisplayAnimation
{
    /**
     * @param int $duration
     * @param int $height
     */
    public function __construct(int $duration = 30, protected int $height = 40)
    {
        parent::__construct($duration);
    }

    /**
     * @inheritDoc
     */
    protected function getInitialTransform(Block $block): string
    {
        return '{left_rotation:[0f,0f,0f,1f],right_rotation:[0f,0f,0f,1f],translation:[0.0f,' . $this->height . 'f,0.0f],scale:[0.0f,0.0f,0.0f]}';
    }
}
