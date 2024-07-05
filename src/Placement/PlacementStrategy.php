<?php

namespace Aternos\Plop\Placement;

use Aternos\Plop\Structure\Structure;

abstract class PlacementStrategy
{
    protected Structure $structure;

    abstract public function getNext(): ?Placement;

    /**
     * @param Structure $structure
     * @return $this
     */
    public function setStructure(Structure $structure): static
    {
        $this->structure = $structure;
        return $this;
    }
}