<?php

namespace Aternos\Plop\Placement;

use Aternos\Plop\Placement\Util\BlockList;
use Aternos\Plop\Structure\Elements\Block;
use Aternos\Plop\Structure\Elements\Element;
use Aternos\Plop\Structure\Structure;

abstract class PlacementStrategy
{
    protected bool $ignoreAir = true;
    public bool $replaceStructureVoid = true;
    protected Structure $structure;
    protected ?BlockList $blockList = null;

    /**
     * @return Placement[]
     */
    abstract public function getPlacements(): array;

    /**
     * @param BlockList|null $blockList
     * @return $this
     */
    public function setBlockList(?BlockList $blockList): static
    {
        $this->blockList = $blockList;
        return $this;
    }

    /**
     * @param Element[] $elements
     * @param int $perTick
     * @return Placement[]
     */
    protected function generatePlacements(array $elements, int $perTick = 1): array
    {
        $tick = 0;
        $placements = [];
        $placement = new Placement();
        foreach ($elements as $element) {
            $placement->addElement($element);
            if ($placement->getElementCount() >= $perTick) {
                $placements[] = $placement;
                $placement = new Placement(tick: ++$tick);
            }
        }
        if ($placement->getElementCount() > 0) {
            $placements[] = $placement;
        }
        return $placements;
    }

    /**
     * @return BlockList
     */
    protected function getBlockList(): BlockList
    {
        return $this->blockList ?? $this->blockList = BlockList::getDefault();
    }

    protected function getElements(): array
    {
        $elements = [];
        foreach ($this->structure->getElements() as $element) {
            if ($element instanceof Block) {
                if (!$this->getBlockList()->isAllowed($element)) {
                    continue;
                }

                if ($this->replaceStructureVoid && $element->isStructureVoid()) {
                    $element = $element->clone()->setName("minecraft:air");
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
