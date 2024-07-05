<?php

namespace Aternos\Plop\Placement;

use Aternos\Plop\Structure\Elements\Block;

class FullPlacementStrategy extends PlacementStrategy
{

    public function getPlacements(): array
    {
        $placement = new Placement();
        foreach ($this->getElements() as $element) {
            if (!$element instanceof Block) {
                $placement->addElement($element);
                continue;
            }
            if ($this->ignoreAir && $element->isAir()) {
                continue;
            }
            $placement->addElement($element);
        }
        return [$placement];
    }
}