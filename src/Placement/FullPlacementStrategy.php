<?php

namespace Aternos\Plop\Placement;

use Aternos\Plop\Structure\Elements\Block;

class FullPlacementStrategy extends PlacementStrategy
{
    protected bool $placed = false;
    protected bool $ignoreAir = true;

    public function getNext(): ?Placement
    {
        if ($this->placed) {
            return null;
        }
        $this->placed = true;

        $placement = new Placement();
        foreach ($this->structure->getElements() as $element) {
            if (!$element instanceof Block) {
                $placement->addElement($element);
                continue;
            }
            if ($this->ignoreAir && $element->isAir()) {
                continue;
            }
            $placement->addElement($element);
        }
        return $placement;
    }

    /**
     * @param bool $ignoreAir
     * @return $this
     */
    public function setIgnoreAir(bool $ignoreAir = true): static
    {
        $this->ignoreAir = $ignoreAir;
        return $this;
    }
}