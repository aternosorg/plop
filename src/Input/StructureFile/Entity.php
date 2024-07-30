<?php

namespace Aternos\Plop\Input\StructureFile;

use Aternos\Nbt\Tag\CompoundTag;
use Aternos\Nbt\Tag\TagType;
use InvalidArgumentException;

class Entity
{
    /**
     * @param CompoundTag $tag
     * @return Entity|null
     * @throws \Exception
     */
    public static function fromNbt(CompoundTag $tag): ?static
    {
        $pos = $tag->getList("pos", TagType::TAG_Double);
        $blockPos = $tag->getList("blockPos", TagType::TAG_Int);
        $nbt = $tag->getCompound("nbt");
        $id = $nbt->getString("id")?->getValue();

        if ($pos === null || count($pos) !== 3 || $blockPos === null || count($blockPos) !== 3 || $nbt === null || !is_string($id)) {
            return null;
        }

        $nbt->offsetUnset("UUID");

        return new static(
            $id,
            $pos[0]->getValue(),
            $pos[1]->getValue(),
            $pos[2]->getValue(),
            $blockPos[0]->getValue(),
            $blockPos[1]->getValue(),
            $blockPos[2]->getValue(),
            $nbt
        );
    }

    /**
     * @param string $id
     * @param float $x
     * @param float $y
     * @param float $z
     * @param int $blockX
     * @param int $blockY
     * @param int $blockZ
     * @param CompoundTag|null $nbt
     */
    public function __construct(
        protected string $id,
        protected float $x,
        protected float $y,
        protected float $z,
        protected int $blockX,
        protected int $blockY,
        protected int $blockZ,
        protected ?CompoundTag $nbt = null
    )
    {
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getX(): float
    {
        return $this->x;
    }

    /**
     * @return float
     */
    public function getY(): float
    {
        return $this->y;
    }

    /**
     * @return float
     */
    public function getZ(): float
    {
        return $this->z;
    }

    /**
     * @return int
     */
    public function getBlockX(): int
    {
        return $this->blockX;
    }

    /**
     * @return int
     */
    public function getBlockY(): int
    {
        return $this->blockY;
    }

    /**
     * @return int
     */
    public function getBlockZ(): int
    {
        return $this->blockZ;
    }

    /**
     * @return ?CompoundTag
     */
    public function getNbt(): ?CompoundTag
    {
        return $this->nbt;
    }
}
