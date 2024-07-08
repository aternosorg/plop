<?php

namespace Aternos\Plop\Animation;

use Aternos\Plop\Structure\Elements\Block;
use Aternos\Plop\Structure\Elements\ElementCommandList;

class ThrowAnimation extends Animation
{
    /**
     * @param int $animationDuration
     * @param int $height
     */
    public function __construct(protected int $animationDuration = 50, protected int $height = 16)
    {
    }

    /**
     * @inheritDoc
     */
    public function getBlockCommands(Block $block, string $prefix): ElementCommandList
    {
        $x = $block->getX();
        $y = $block->getY();
        $z = $block->getZ();
        $tag = $prefix . $block::TAG . "_" . $x . "_" . $y . "_" . $z;
        $objective = $prefix . $block::TAG;
        $tags = [
            $prefix . $block::TAG,
            $tag
        ];

        $height = $block->getY() + $this->height;
        $distanceY = $height + $this->height;
        $distanceUpPart  = $height / $distanceY;
        $distanceDownPart = 1 - $distanceUpPart;

        $downAnimationTime = floor($this->animationDuration*$distanceDownPart*0.8);
        $upAnimationTime = $this->animationDuration - $downAnimationTime;

        $middleScale = 0.999 * $distanceUpPart;

        $initTransform = '{left_rotation:[0f,0f,0f,1f],right_rotation:[0f,0f,0f,1f],translation:[' . -$x . 'f,' . -$y . 'f,' . -$z . 'f],scale:[0f,0f,0f]}';
        $middleTransform = '{translation:[' . -$x*$distanceUpPart . 'f,' . $this->height . 'f,' . -$z*$distanceUpPart . 'f],scale:[' . $middleScale . 'f,' . $middleScale . 'f,' . $middleScale . 'f]}';
        $endTransform = '{translation:[0f,0f,0f],scale:[0.999f,0.999f,0.999f]}';

        return new ElementCommandList([
            'summon minecraft:block_display ~' . number_format($x, 1) . ' ~' . number_format($y, 1) . ' ~' . number_format($z, 1) . ' {shadow_strength:0f,interpolation_duration:' . $upAnimationTime . ',Tags:' . json_encode($tags) . ',transformation:' . $initTransform . ',block_state:{Name:' . json_encode($block->getName()) . ', Properties:' . json_encode($block->getState()) . '}}'
        ], [
            'execute as @e[tag=' . $tag . ',scores={' . $objective . '=1}] run data merge entity @s {start_interpolation:0,transformation:' . $middleTransform . '}',
            'execute as @e[tag=' . $tag . ',scores={' . $objective . '=' . floor($this->animationDuration*$distanceUpPart)-1 . '}] run data merge entity @s {interpolation_duration:' . $downAnimationTime . ',start_interpolation:0,transformation:' . $endTransform . '}',
            'execute as @e[tag=' . $tag . ',scores={' . $objective . '=' . $this->animationDuration . '..}] run setblock ' . $block->getRelativeCoordinatesString() . " " . $block->getName() . $block->getStateString() . $block->getNBTString(),
            'execute as @e[tag=' . $tag . ',scores={' . $objective . '=' . $this->animationDuration + 1 . '..}] run kill @s',
        ]);
    }
}
