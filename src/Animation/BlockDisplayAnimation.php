<?php

namespace Aternos\Plop\Animation;

use Aternos\Nbt\Tag\CompoundTag;
use Aternos\Nbt\Tag\ListTag;
use Aternos\Nbt\Tag\StringTag;
use Aternos\Plop\Output\TimedCommand;
use Aternos\Plop\Structure\Elements\Block;

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
     * @param int $duration
     */
    public function __construct(public int $duration = 4)
    {
    }

    /**
     * @inheritDoc
     */
    public function getBlockCommands(Block $block, string $prefix): array
    {
        $x = $block->getX();
        $y = $block->getY();
        $z = $block->getZ();
        $tag = $prefix . $block::TAG . "_" . $x . "_" . $y . "_" . $z;
        $tags = [
            $prefix . $block::TAG,
            $tag
        ];

        return [
            new TimedCommand(
                'summon minecraft:block_display ' . $block->getRelativeCoordinatesString(true) .
                ' {shadow_strength:0f,interpolation_duration:' . $this->duration .
                ',Tags:' . static::encodeSNBTStringList($tags) .
                ',transformation:' . $this->getInitialTransform($block) .
                ',block_state:{Name:' . StringTag::encodeSNBTString($block->getName()) .
                ', Properties:' . static::encodeSNBTStringCompound($block->getState()) . '}}',
                positioned: true),
            new TimedCommand('execute as @e[tag=' . $tag . ',limit=1] run data merge entity @s {start_interpolation:0,transformation:{translation:[0f,0f,0f],scale:[0.999f,0.999f,0.999f]}}', 1),
            new TimedCommand('setblock ' . $block->getRelativeCoordinatesString() . ' ' . $block->getDefinition(), $this->duration, true),
            new TimedCommand('execute as @e[tag=' . $tag . ',limit=1] run kill @s', $this->duration + 1),
        ];
    }

    /**
     * @param Block $block
     * @return string
     */
    abstract protected function getInitialTransform(Block $block): string;
}
