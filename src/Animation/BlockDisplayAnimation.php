<?php

namespace Aternos\Plop\Animation;

use Aternos\Plop\Structure\Elements\Block;
use Aternos\Plop\Structure\Elements\ElementCommandList;

abstract class BlockDisplayAnimation extends Animation
{
    /**
     * @param int $animationDuration
     */
    public function __construct(protected int $animationDuration = 4)
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

        return new ElementCommandList([
            'summon minecraft:block_display ~' . number_format($x, 1) . ' ~' . number_format($y, 1) . ' ~' . number_format($z, 1) . ' {shadow_strength:0f,interpolation_duration:' . $this->animationDuration . ',Tags:' . json_encode($tags) . ',transformation:' . $this->getInitialTransform($block) . ',block_state:{Name:' . json_encode($block->getName()) . ', Properties:' . json_encode($block->getState()) . '}}'
        ], [
            'execute as @e[tag=' . $tag . ',scores={' . $objective . '=1}] run data merge entity @s {start_interpolation:0,transformation:{translation:[0f,0f,0f],scale:[0.999f,0.999f,0.999f]}}',
            'execute as @e[tag=' . $tag . ',scores={' . $objective . '=' . $this->animationDuration . '..}] run setblock ' . $block->getRelativeCoordinatesString() . " " . $block->getName() . $block->getStateString() . $block->getNBTString(),
            'execute as @e[tag=' . $tag . ',scores={' . $objective . '=' . $this->animationDuration + 1 . '..}] run kill @s',
        ]);
    }

    /**
     * @param Block $block
     * @return string
     */
    abstract protected function getInitialTransform(Block $block): string;
}
