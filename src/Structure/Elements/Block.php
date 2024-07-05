<?php

namespace Aternos\Plop\Structure\Elements;

use Aternos\Plop\Animation\Animation;

class Block extends Element
{
    protected ?Animation $animation = null;

    public function __construct(string $name, float $x, float $y, float $z, protected array $state = [], protected ?string $nbt = null)
    {
        parent::__construct($name, $x, $y, $z);
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

    public function getSetBlockCommand(): string
    {
        return "setblock " . $this->getRelativeCoordinatesString() . " " . $this->name . $this->getStateString() . $this->getNBTString();
    }

    public function isAir(): bool
    {
        return $this->getName() === "minecraft:air";
    }
}