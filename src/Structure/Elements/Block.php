<?php

namespace Aternos\Plop\Structure\Elements;

use Aternos\Plop\Animation\Animation;

class Block extends AnimatableElement
{
    const string TAG = "block";
    const array AIR = [
        "minecraft:air",
        "minecraft:void_air",
        "minecraft:cave_air"
    ];

    public function __construct(string $name, float $x, float $y, float $z, ?string $nbt = null, protected array $state = [])
    {
        parent::__construct($name, $x, $y, $z, $nbt);
    }

    /**
     * @return array
     */
    public function getState(): array
    {
        return $this->state;
    }

    public function getStateString(): string
    {
        if (empty($this->state)) {
            return "";
        }

        $states = [];
        foreach ($this->state as $key => $value) {
            $states[] = $key . "=" . $value;
        }
        return "[" . implode(",", $states) . "]";
    }

    public function getNBTString(): string
    {
        return $this->nbt ?? "";
    }

    public function getCommands(string $prefix, int $tick): ElementCommandList
    {
        if ($this->animation !== null) {
            return $this->animation->getBlockCommands($this, $prefix, $tick);
        }
        return new ElementCommandList(["setblock " . $this->getRelativeCoordinatesString() . " " . $this->name . $this->getStateString() . $this->getNBTString()]);
    }

    /**
     * @return bool
     */
    public function isAir(): bool
    {
        return in_array($this->getName(), static::AIR);
    }

    /**
     * @return bool
     */
    public function isStructureVoid(): bool
    {
        return $this->getName() === "minecraft:structure_void";
    }

    /**
     * @param Animation|null $animation
     * @return $this
     */
    public function setAnimation(?Animation $animation): static
    {
        $this->animation = $animation;
        return $this;
    }

    /**
     * @return $this
     */
    public function clone(): static
    {
        return new static($this->name, $this->x, $this->y, $this->z, $this->nbt, $this->state);
    }
}
