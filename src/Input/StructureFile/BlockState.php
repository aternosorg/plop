<?php

namespace Aternos\Plop\Input\StructureFile;

use Aternos\Nbt\Tag\CompoundTag;
use Aternos\Nbt\Tag\StringTag;
use InvalidArgumentException;

class BlockState
{
    /**
     * @param CompoundTag $tag
     * @return static
     */
    static function fromNbt(CompoundTag $tag): static
    {
        $name = $tag->getString("Name")?->getValue();
        if (!is_string($name)) {
            throw new InvalidArgumentException("Invalid BlockState NBT data");
        }

        $properties = null;
        if ($props = $tag->getCompound("Properties")) {
            $properties = [];
            foreach ($props as $key => $value) {
                if (!($value instanceof StringTag)) {
                    throw new InvalidArgumentException("Invalid BlockState NBT data");
                }
                $properties[$key] = $value->getValue();
            }
        }

        return new static($name, $properties);
    }

    /**
     * @param string $name
     * @param string[]|null $properties
     */
    public function __construct(protected string $name, protected ?array $properties = null)
    {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return null|string[]
     */
    public function getProperties(): ?array
    {
        return $this->properties;
    }
}
