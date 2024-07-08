<?php

namespace Aternos\Plop\Placement;

use Aternos\Plop\Structure\Elements\Element;

class SameMaterialPlacementStrategy extends PlacementStrategy
{
    protected array $placed = [];
    protected array $elements = [];

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
        $this->elements = $this->getElements();
        $this->placed = [];
        $i = 0;

        while ($start = $this->findStartingPoint()) {
            $found = $this->propagate($start);
            array_unshift($found, $start);
            array_push($this->placed, ...$found);
            while (count($found) > 0) {
                $placement = new Placement([], $i++);
                for ($j = 0; $j < $this->elementsPerTick && count($found) > 0; $j++) {
                    $placement->addElement(array_shift($found));
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
        foreach ($this->elements as $element) {
            if ($element === $start || in_array($element, $found) || in_array($element, $this->placed)){
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
        foreach ($this->elements as $element) {
            if (in_array($element, $this->placed)) {
                continue;
            }
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
