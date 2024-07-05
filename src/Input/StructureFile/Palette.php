<?php

namespace Aternos\Plop\Input\StructureFile;

use Aternos\Nbt\Tag\CompoundTag;
use Aternos\Nbt\Tag\ListTag;
use InvalidArgumentException;

class Palette
{
    /**
     * @param ListTag $tag
     * @return static
     */
    public static function fromNbt(ListTag $tag): static
    {
        $states = [];
        foreach ($tag as $stateTag) {
            if (!$stateTag instanceof CompoundTag) {
                throw new InvalidArgumentException("Invalid palette NBT data");
            }
            $states[] = BlockState::fromNbt($stateTag);
        }
        return new static($states);
    }

    /**
     * @param BlockState[] $states
     */
    public function __construct(protected array $states)
    {
    }

    /**
     * @param int $index
     * @return BlockState
     */
    public function get(int $index): BlockState
    {
        if (!isset($this->states[$index])) {
            throw new InvalidArgumentException("Invalid palette index");
        }
        return $this->states[$index];
    }
}
