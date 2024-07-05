<?php

namespace Aternos\Plop\Input\StructureFile;

use Aternos\Nbt\Tag\CompoundTag;
use Aternos\Nbt\Tag\TagType;
use InvalidArgumentException;

class Block
{
    /**
     * @param CompoundTag $tag
     * @param Palette $palette
     * @return static
     */
    static function fromNbt(CompoundTag $tag, Palette $palette): static
    {
        $pos = $tag->getList("pos", TagType::TAG_Int);
        $state = $tag->getInt("state")?->getValue();

        if ($pos === null || count($pos) !== 3 || $state === null) {
            throw new InvalidArgumentException("Invalid Block NBT data: " . $tag->toSNBT());
        }

        return new static(
            $pos[0]->getValue(),
            $pos[1]->getValue(),
            $pos[2]->getValue(),
            $palette->get($state),
            $tag->getCompound("nbt")
        );
    }

    /**
     * @param int $x
     * @param int $y
     * @param int $z
     * @param BlockState $state
     * @param CompoundTag|null $nbt
     */
    public function __construct(protected int          $x,
                                protected int          $y,
                                protected int          $z,
                                protected BlockState          $state,
                                protected ?CompoundTag $nbt = null
    )
    {
    }

    /**
     * @return int
     */
    public function getX(): int
    {
        return $this->x;
    }

    /**
     * @return int
     */
    public function getY(): int
    {
        return $this->y;
    }

    /**
     * @return int
     */
    public function getZ(): int
    {
        return $this->z;
    }

    /**
     * @return BlockState
     */
    public function getState(): BlockState
    {
        return $this->state;
    }

    /**
     * @return CompoundTag|null
     */
    public function getNbt(): ?CompoundTag
    {
        return $this->nbt;
    }
}
