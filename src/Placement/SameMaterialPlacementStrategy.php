<?php

namespace Aternos\Plop\Placement;

use Aternos\Plop\Structure\Elements\Element;
use Aternos\Plop\Structure\Elements\ElementCollection;

class SameMaterialPlacementStrategy extends PlacementStrategy
{
    protected ElementCollection $elements;

    /**
     * @param int $perTick
     */
    public function __construct(public int $perTick = 1)
    {
    }

    /**
     * @inheritDoc
     */
    public function getPlacements(): array
    {
        $resultElements = [];
        $this->elements = ElementCollection::fromArray($this->getElements());

        while ($start = $this->findStartingPoint()) {
            $found = $this->propagate($start);
            array_unshift($found, $start);
            foreach ($found as $element) {
                $resultElements[] = $element;
                $this->elements->remove($element);
            }
        }

        return $this->generatePlacements($resultElements, $this->perTick);
    }

    /**
     * @param Element $start
     * @param Element[] $found
     * @return Element[]
     */
    protected function propagate(Element $start, array $found = []): array
    {
        foreach ($this->elements->getAdjacentToElement($start, 1) as $element) {
            if (in_array($element, $found)) {
                continue;
            }
            if ($element->getName() === $start->getName()) {
                $found[] = $element;
                $found = $this->propagate($element, $found);
            }
        }
        return $found;
    }


    /**
     * @return Element|null
     */
    protected function findStartingPoint(): ?Element
    {
        $minY = PHP_INT_MAX;
        $lowest = [];
        foreach ($this->elements->getAll() as $element) {
            if ($element->getY() < $minY) {
                $minY = $element->getY();
                $lowest = [$element];
            } elseif ($element->getY() === $minY) {
                $lowest[] = $element;
            }
        }

        if (count($lowest) === 0) {
            return null;
        }

        return $lowest[array_rand($lowest)];
    }
}
