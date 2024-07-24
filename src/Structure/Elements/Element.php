<?php

namespace Aternos\Plop\Structure\Elements;

use Aternos\Plop\Output\TimedCommand;
use Aternos\Plop\Placement\Util\Axis;

abstract class Element
{
    public function __construct(
        protected string  $name,
        protected float   $x,
        protected float   $y,
        protected float   $z,
        protected ?string $nbt = null
    )
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAxis(Axis $axis): float
    {
        return match ($axis) {
            Axis::X => $this->x,
            Axis::Y => $this->y,
            Axis::Z => $this->z,
        };
    }

    public function getX(): float
    {
        return $this->x;
    }

    public function getY(): float
    {
        return $this->y;
    }

    public function getZ(): float
    {
        return $this->z;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function setX(float $x): static
    {
        $this->x = $x;
        return $this;
    }

    public function setY(float $y): static
    {
        $this->y = $y;
        return $this;
    }

    public function setZ(float $z): static
    {
        $this->z = $z;
        return $this;
    }

    public function setNbt(?string $nbt): static
    {
        $this->nbt = $nbt;
        return $this;
    }

    public function getRelativeCoordinatesString(bool $forceFloat = false): string
    {
        if ($forceFloat) {
            return "~" . number_format($this->x, 1) . " ~" . number_format($this->y, 1) . " ~" . number_format($this->z, 1);
        }
        return "~" . $this->x . " ~" . $this->y . " ~" . $this->z;
    }

    /**
     * @param string $prefix
     * @return TimedCommand[]
     */
    abstract public function getCommands(string $prefix): array;
}
