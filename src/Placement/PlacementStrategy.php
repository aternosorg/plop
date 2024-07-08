<?php

namespace Aternos\Plop\Placement;

use Aternos\Plop\Structure\Elements\Block;
use Aternos\Plop\Structure\Structure;

abstract class PlacementStrategy
{
    protected bool $ignoreAir = true;
    protected bool $replaceStructureVoid = true;
    protected Structure $structure;

    /**
     * @return Placement[]
     */
    abstract public function getPlacements(): array;

    protected function getElements(): array
    {
        $elements = [];
        foreach ($this->structure->getElements() as $element) {
            if ($element instanceof Block) {
                if ($this->ignoreAir && $element->isAir()) {
                    continue;
                }

                if ($this->replaceStructureVoid && $element->isStructureVoid()) {
                    $element = new Block(
                        $element->getName(),
                        $element->getX(),
                        $element->getY(),
                        $element->getZ(),
                        $element->getNBTString(),
                        $element->getState()
                    );
                }
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
     * @param bool $replaceStructureVoid
     * @return $this
     */
    public function setReplaceStructureVoid(bool $replaceStructureVoid = true): static
    {
        $this->replaceStructureVoid = $replaceStructureVoid;
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
