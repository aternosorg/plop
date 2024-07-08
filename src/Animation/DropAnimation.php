<?php

namespace Aternos\Plop\Animation;

use Aternos\Plop\Structure\Elements\Block;

class DropAnimation extends BlockDisplayAnimation
{
    /**
     * @param int $animationDuration
     * @param int $dropHeight
     */
    public function __construct(int $animationDuration = 30, protected int $dropHeight = 40)
    {
        parent::__construct($animationDuration);
    }

    /**
     * @inheritDoc
     */
    protected function getInitialTransform(Block $block): string
    {
        return '{left_rotation:[0f,0f,0f,1f],right_rotation:[0f,0f,0f,1f],translation:[0.0f,' . $this->dropHeight . 'f,0.0f],scale:[0.0f,0.0f,0.0f]}';
    }
}
