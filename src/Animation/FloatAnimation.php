<?php

namespace Aternos\Plop\Animation;

use Aternos\Plop\Structure\Elements\Block;

class FloatAnimation extends BlockDisplayAnimation
{
    public function __construct(int $duration = 20, public int $x = 0, public int $y = 0, public int $z = 0)
    {
        parent::__construct($duration);
    }

    /**
     * @inheritDoc
     */
    protected function getInitialTransform(Block $block): string
    {
        $x = -$block->getX() + $this->x;
        $y = -$block->getY() + $this->y;
        $z = -$block->getZ() + $this->z;

        return '{left_rotation:[0f,0f,0f,1f],right_rotation:[0f,0f,0f,1f],translation:[' . $x . 'f,' . $y . 'f,' . $z . 'f],scale:[0.0f,0.0f,0.0f]}';
    }
}
