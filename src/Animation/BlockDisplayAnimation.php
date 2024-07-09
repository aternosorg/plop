<?php

namespace Aternos\Plop\Animation;

use Aternos\Nbt\Tag\CompoundTag;
use Aternos\Nbt\Tag\ListTag;
use Aternos\Nbt\Tag\StringTag;
use Aternos\Plop\Structure\Elements\Block;
use Aternos\Plop\Structure\Elements\ElementCommandList;

abstract class BlockDisplayAnimation extends Animation
{
    /**
     * @param array $input
     * @return string
     */
    public static function encodeSNBTStringList(array $input): string
    {
        $list = new ListTag();
        foreach ($input as $item) {
            $list[] = (new StringTag())->setValue($item);
        }
        return $list->toSNBT();
    }

    /**
     * @param string[] $input
     * @return string
     */
    public static function encodeSNBTStringCompound(array $input): string
    {
        $compound = new CompoundTag();
        foreach ($input as $key => $item) {
            $compound[$key] = (new StringTag())->setValue($item);
        }
        return $compound->toSNBT();
    }

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
            'summon minecraft:block_display ~' . number_format($x, 1) . ' ~' . number_format($y, 1) . ' ~' . number_format($z, 1) . ' {shadow_strength:0f,interpolation_duration:' . $this->animationDuration . ',Tags:' . static::encodeSNBTStringList($tags) . ',transformation:' . $this->getInitialTransform($block) . ',block_state:{Name:' . StringTag::encodeSNBTString($block->getName()) . ', Properties:' . static::encodeSNBTStringCompound($block->getState()) . '}}'
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
