<?php

namespace Aternos\Plop\Placement;

use Aternos\Plop\Structure\Elements\Block;
use Aternos\Plop\Structure\Structure;

abstract class PlacementStrategy
{
    protected bool $ignoreAir = true;
    protected Structure $structure;

    /**
     * @return Placement[]
     */
    abstract public function getPlacements(): array;

    protected function getElements(): array
    {
        $elements = [];
        foreach ($this->structure->getElements() as $element) {
            if ($element instanceof Block && $element->isAir()) {
                continue;
            }
            $elements[] = $element;
        }
        return $elements;
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