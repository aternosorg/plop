<?php

namespace Aternos\Plop\Input\StructureFile;

use Aternos\Nbt\IO\Reader\GZipCompressedStringReader;
use Aternos\Nbt\NbtFormat;
use Aternos\Nbt\Tag\CompoundTag;
use Aternos\Nbt\Tag\Tag;
use Aternos\Nbt\Tag\TagType;
use Aternos\Plop\Input\Input;
use Aternos\Plop\Structure\Structure;
use Exception;
use InvalidArgumentException;

class StructureFile extends Input
{
    /**
     * @param string $filename
     * @return static
     * @throws Exception
     */
    public static function loadFile(string $filename): static
    {
        $content = @file_get_contents($filename);
        if ($content === false) {
            throw new InvalidArgumentException("Failed to read file: $filename");
        }

        return static::loadString($content);
    }

    /**
     * @param string $content
     * @return static
     * @throws Exception
     */
    public static function loadString(string $content): static
    {
        $tag = Tag::load(new GZipCompressedStringReader($content, NbtFormat::JAVA_EDITION));
        if (!$tag instanceof CompoundTag) {
            throw new InvalidArgumentException("Invalid Structure File NBT data.");
        }
        return static::fromNbt($tag);
    }

    /**
     * @param CompoundTag $tag
     * @return static
     */
    public static function fromNbt(CompoundTag $tag): static
    {
        $size = $tag->getList("size", TagType::TAG_Int);
        $paletteData = $tag->getList("palette", TagType::TAG_Compound);
        $blocksData = $tag->getList("blocks", TagType::TAG_Compound);
        $entitiesData = $tag->getList("entities", TagType::TAG_Compound);

        if (!$size || count($size) !== 3 || !$paletteData || !$blocksData || !$entitiesData) {
            throw new InvalidArgumentException("Invalid Structure File NBT data.");
        }

        $palette = Palette::fromNbt($paletteData);
        $blocks = [];
        foreach ($blocksData as $blockTag) {
            if (!$blockTag instanceof CompoundTag) {
                throw new InvalidArgumentException("Invalid Structure File NBT data.");
            }
            $blocks[] = Block::fromNbt($blockTag, $palette);
        }

        $entities = [];
        foreach ($entitiesData as $entityTag) {
            if (!$entityTag instanceof CompoundTag) {
                throw new InvalidArgumentException("Invalid Structure File NBT data.");
            }
            $entities[] = Entity::fromNbt($entityTag);
        }

        return new static(
            $size[0]->getValue(),
            $size[1]->getValue(),
            $size[2]->getValue(),
            $blocks,
            $palette,
            $entities
        );
    }

    /**
     * @param int $sizeX
     * @param int $sizeY
     * @param int $sizeZ
     * @param array $blocks
     * @param Palette $palette
     * @param array $entities
     */
    public function __construct(
        protected int $sizeX,
        protected int $sizeY,
        protected int $sizeZ,
        protected array $blocks,
        protected Palette $palette,
        protected array $entities
    )
    {
    }

    public function getStructure(): Structure
    {
        // TODO: Implement getStructure() method.
    }
}
