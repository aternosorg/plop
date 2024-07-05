<?php

namespace Aternos\Plop\Structure\Elements;

use Aternos\Plop\Animation\Animation;
use Aternos\Plop\Animation\PlopAnimation;

class Block extends Element
{
    const string TAG = "block";
    protected ?Animation $animation = null;

    public function __construct(string $name, float $x, float $y, float $z, ?string $nbt = null, protected array $state = [])
    {
        parent::__construct($name, $x, $y, $z, $nbt);
        $this->animation = new PlopAnimation();
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

    public function getCommands(string $prefix): ElementCommandList
    {
        if ($this->animation !== null) {
            return $this->animation->getBlockCommands($this, $prefix);
        }
        return new ElementCommandList(["setblock " . $this->getRelativeCoordinatesString() . " " . $this->name . $this->getStateString() . $this->getNBTString()]);
    }

    public function isAir(): bool
    {
        return $this->getName() === "minecraft:air";
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
}
