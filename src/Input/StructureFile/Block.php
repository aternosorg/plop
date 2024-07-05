<?php

namespace Aternos\Plop\Input\StructureFile;

use Aternos\Nbt\Tag\CompoundTag;

class Block
{
    public function __construct(protected int          $x,
                                protected int          $y,
                                protected int          $z,
                                protected int          $state,
                                protected ?CompoundTag $nbt = null
    )
    {
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function getZ(): int
    {
        return $this->z;
    }

    public function getState(): int
    {
        return $this->state;
    }

    public function getNbt(): ?CompoundTag
    {
        return $this->nbt;
    }
}
