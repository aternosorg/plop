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

        $placements = [];
        $tick = 0;
        $elementsInTick = 0;
        foreach ($elements as $element) {
            $placements[] = new Placement([$element], $tick);
            $elementsInTick++;
            if ($elementsInTick >= $this->perTick) {
                $elementsInTick = 0;
                $tick++;
            }
        }
        return $placements;
    }
}