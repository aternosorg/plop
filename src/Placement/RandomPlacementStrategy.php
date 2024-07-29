<?php

namespace Aternos\Plop\Placement;

class RandomPlacementStrategy extends PlacementStrategy
{
    public function __construct(public int $perTick = 1)
    {
    }

    /**
     * @inheritDoc
     */
    public function getPlacements(): array
    {
        $elements = $this->getElements();
        shuffle($elements);

        return $this->generatePlacements($elements, $this->perTick);
    }
}