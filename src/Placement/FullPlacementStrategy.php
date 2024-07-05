<?php

namespace Aternos\Plop\Placement;

class FullPlacementStrategy extends PlacementStrategy
{
    protected bool $placed = false;

    public function getNext(): ?Placement
    {
        if ($this->placed) {
            return null;
        }
        $this->placed = true;
        return new Placement($this->structure->getElements());
    }
}