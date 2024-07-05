<?php

namespace Aternos\Plop\Animation;

use Aternos\Plop\Animation\Animation;
use Aternos\Plop\Structure\Elements\Block;

class PlopAnimation extends Animation
{

    /**
     * @inheritDoc
     */
    public function getBlockCommands(Block $block, string $startIf, string $prefix): array
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

        return [
            'execute ' . $startIf . ' run summon minecraft:block_display ~' . number_format($x,1) . ' ~' . number_format($y,1) . ' ~' . number_format($z,1) . ' {shadow_strength:0f,interpolation_duration:2,Tags:' . json_encode($tags) . ',transformation:{left_rotation:[0f,0f,0f,1f],right_rotation:[0f,0f,0f,1f],translation:[0.45f,0.45f,0.45f],scale:[0.1f,0.1f,0.1f]},block_state:{Name:' . json_encode($block->getName()) . ', Properties:' . json_encode($block->getState()) . '}}',
            'execute as @e[tag=' . $tag . ',scores={' . $objective . '=1}] run data merge entity @s {start_interpolation:0,transformation:{translation:[0f,0f,0f],scale:[1f,1f,1f]}}',
            'execute as @e[tag=' . $tag . ',scores={' . $objective . '=4..}] run setblock ' . $block->getRelativeCoordinatesString() . " " . $block->getName() . $block->getStateString() . $block->getNBTString(),
            'execute as @e[tag=' . $tag . ',scores={' . $objective . '=4..}] run kill @s',
        ];
    }
}
