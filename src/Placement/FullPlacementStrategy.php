<?php

namespace Aternos\Plop\Placement;

use Aternos\Plop\Structure\Elements\Block;

class FullPlacementStrategy extends PlacementStrategy
{
    protected bool $ignoreAir = true;

    /**
     * @param bool $ignoreAir
     * @return $this
     */
    public function setIgnoreAir(bool $ignoreAir = true): static
    {
        $this->ignoreAir = $ignoreAir;
        return $this;
    }

    public function getPlacements(): array
    {
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
        return [$placement];
    }
}