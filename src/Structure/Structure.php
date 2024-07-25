<?php

namespace Aternos\Plop\Structure;

use Aternos\Plop\Animation\Animation;
use Aternos\Plop\Placement\Util\Axis;
use Aternos\Plop\Structure\Elements\AnimatableElement;
use Aternos\Plop\Structure\Elements\Block;
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

    public function setDefaultAnimation(?Animation $animation = null): static
    {
        foreach ($this->getElements() as $element) {
            if ($element instanceof AnimatableElement) {
                $element->setAnimation($animation);
            }
        }
        return $this;
    }

    public function findAndAddStructureVoids(): static
    {
        $indexedElements = [];
        foreach ($this->getElements() as $element) {
            if (!$element instanceof Block) {
                continue;
            }
            if (!isset($indexedElements[(int)$element->getX()])) {
                $indexedElements[(int)$element->getX()] = [];
            }
            if (!isset($indexedElements[(int)$element->getX()][(int)$element->getY()])) {
                $indexedElements[(int)$element->getX()][(int)$element->getY()] = [];
            }
            $indexedElements[(int)$element->getX()][(int)$element->getY()][(int)$element->getZ()] = $element;
        }

        for ($x = 0; $x < $this->getSizeX(); $x++) {
            for ($y = 0; $y < $this->getSizeY(); $y++) {
                for ($z = 0; $z < $this->getSizeZ(); $z++) {
                    if (!isset($indexedElements[$x][$y][$z])) {
                        $this->addElement(new Block(Block::STRUCTURE_VOID, $x, $y, $z));
                    }
                }
            }
        }

        return $this;
    }
}