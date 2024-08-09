<?php

namespace Aternos\Plop\Placement\Util;

use Aternos\Plop\Structure\Elements\Block;

class BlockList
{
    /**
     * @return static
     */
    public static function getDefault(): static
    {
        return new static([
            "minecraft:air",
            "minecraft:void_air",
            "minecraft:cave_air"
        ], false);
    }

    /**
     * @param string|null $blockList
     * @return static
     */
    public static function fromString(?string $blockList): static
    {
        if ($blockList === null) {
            return static::getDefault();
        }
        $allow = true;
        if (str_starts_with($blockList, "~")) {
            $allow = false;
            $blockList = substr($blockList, 1);
        }
        return new static(explode(",", $blockList), $allow);
    }

    /**
     * @param array $blocks
     * @param bool $allow
     */
    public function __construct(protected array $blocks, protected bool $allow = true)
    {
    }

    /**
     * @param string|Block $block
     * @return bool
     */
    public function isAllowed(string|Block $block): bool
    {
        if ($this->allow) {
            return $this->contains($block);
        }
        return !$this->contains($block);
    }

    /**
     * @param string|Block $block
     * @return bool
     */
    public function contains(string|Block $block): bool
    {
        if ($block instanceof Block) {
            $block = $block->getName();
        }
        return in_array($block, $this->blocks);
    }

    /**
     * @param string $block
     * @return $this
     */
    public function addBlock(string $block): static
    {
        if (!in_array($block, $this->blocks)) {
            $this->blocks[] = $block;
        }
        return $this;
    }

    /**
     * @param string $block
     * @return $this
     */
    public function removeBlock(string $block): static
    {
        foreach ($this->blocks as $key => $value) {
            if ($value === $block) {
                unset($this->blocks[$key]);
            }
        }
        return $this;
    }
}