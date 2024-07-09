<?php

namespace Aternos\Plop\Placement;

use Aternos\Plop\Structure\Elements\Element;
use Aternos\Plop\Structure\Elements\ElementCollection;

class SameMaterialPlacementStrategy extends PlacementStrategy
{
    protected ElementCollection $elements;

    /**
     * @param int $elementsPerTick
     */
    public function __construct(protected int $elementsPerTick = 1)
    {
    }

    /**
     * @inheritDoc
     */
    public function getPlacements(): array
    {
        $placements = [];
        $this->elements = ElementCollection::fromArray($this->getElements());
        $i = 0;

        while ($start = $this->findStartingPoint()) {
            $found = $this->propagate($start);
            array_unshift($found, $start);
            while (count($found) > 0) {
                $placement = new Placement([], $i++);
                for ($j = 0; $j < $this->elementsPerTick && count($found) > 0; $j++) {
                    $element = array_shift($found);
                    $placement->addElement($element);
                    $this->elements->remove($element);
                }
                $placements[] = $placement;
            }
        }

        return $placements;
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
            if ($element->getName() === $start->getName() && $this->isElementNextTo($start, $element)) {
                $found[] = $element;
                $found = $this->propagate($element, $found);
            }
        }
        return $found;
    }

    /**
     * @param Element $element
     * @param Element $other
     * @param float $distance
     * @return bool
     */
    protected function isElementNextTo(Element $element, Element $other, float $distance = 1.1): bool
    {
        return abs($element->getX() - $other->getX()) +
            abs($element->getY() - $other->getY()) +
            abs($element->getZ() - $other->getZ()) <= $distance;
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
