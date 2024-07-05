<?php

namespace Aternos\Plop\Structure;

use Aternos\Plop\Placement\Util\Axis;
use Aternos\Plop\Structure\Elements\Element;

class Structure
{
    protected array $elements = [];

    public function __construct(
        protected int $sizeX,
        protected int $sizeY,
        protected int $sizeZ
    )
    {
    }

    public function addElement(Element $element): void
    {
        $this->elements[] = $element;
    }

    public function getElements(): array
    {
        return $this->elements;
    }

    public function getSizeX(): int
    {
        return $this->sizeX;
    }

    public function getSizeY(): int
    {
        return $this->sizeY;
    }

    public function getSizeZ(): int
    {
        return $this->sizeZ;
    }

    public function getAxisSize(Axis $axis): int
    {
        return match ($axis) {
            Axis::X => $this->getSizeX(),
            Axis::Y => $this->getSizeY(),
            Axis::Z => $this->getSizeZ(),
        };
    }


}