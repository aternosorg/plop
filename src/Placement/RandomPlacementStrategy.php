<?php

namespace Aternos\Plop\Placement;

use Aternos\Plop\Structure\Elements\Block;

class RandomPlacementStrategy extends PlacementStrategy
{
    /**
     * @inheritDoc
     */
    public function getPlacements(): array
    {
        $elements = $this->structure->getElements();
        shuffle($elements);

        $placements = [];
        $i = 0;
        foreach ($elements as $element) {
            if ($element instanceof Block && $element->isAir()) {
                continue;
            }
            $placements[] = new Placement([$element], $i);
            $i++;
        }
        return $placements;
    }
}