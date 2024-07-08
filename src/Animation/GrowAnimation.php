<?php

namespace Aternos\Plop\Animation;

class GrowAnimation extends BlockDisplayAnimation
{
    /**
     * @inheritDoc
     */
    protected function getInitialTransform(): string
    {
        return '{left_rotation:[0f,0f,0f,1f],right_rotation:[0f,0f,0f,1f],translation:[0.0f,0.0f,0.0f],scale:[0.999f,0.0f,0.999f]}';
    }
}
