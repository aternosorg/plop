<?php

namespace Aternos\Plop\Structure\Elements;
class Element
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

    public function getRelativeCoordinatesString(): string
    {
        return "~" . $this->x . " ~" . $this->y . " ~" . $this->z;
    }
}