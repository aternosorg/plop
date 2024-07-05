<?php

namespace Aternos\Plop\Placement;

use Aternos\Plop\Structure\Elements\Element;

class PropagatingPlacementStrategy extends PlacementStrategy
{
    public function __construct(protected int $placementSize = 1)
    {
    }

    /**
     * @inheritDoc
     */
    public function getPlacements(): array
    {
        $placements = [];
        $elements = $this->getElements();
        $i = 0;
        while ($current = $this->findStartingPoint($elements)) {
            $this->propagate($elements, $placements, $current, null, $i, true);
        }

        return $placements;
    }

    protected function propagate(array &$elements, array &$placements, Element $currentElement, ?Placement $currentPlacement, int &$i, bool $allowY = false): void
    {
        if ($currentPlacement && count($currentPlacement->getElements()) < $this->placementSize) {
            $currentPlacement->addElement($currentElement);
        } else {
            $currentPlacement = new Placement([$currentElement], $i++);
            $placements[] = $currentPlacement;
        }
        unset($elements[array_search($currentElement, $elements)]);

        while ($nextElement = $this->findNext($currentElement, $elements)) {
            $this->propagate($elements, $placements, $nextElement, $currentPlacement, $i);
        }

        if ($allowY) {
            while ($nextElement = $this->findNext($currentElement, $elements, true)) {
                $this->propagate($elements, $placements, $nextElement, $currentPlacement, $i, true);
            }
        }
    }

    /**
     * @param Element $current
     * @param array $elements
     * @param bool $allowY
     * @param float $maxDistance
     * @return Element|null
     */
    protected function findNext(Element $current, array $elements, bool $allowY = false, float $maxDistance = 1.2): ?Element
    {
        foreach ($elements as $element) {
            if ($current->getY() === $element->getY() && $this->isElementWithin($element, $current, $maxDistance)) {
                return $element;
            }
        }

        if ($allowY) {
            foreach ($elements as $element) {
                if ($this->isElementWithin($element, $current, 1.0)) {
                    return $element;
                }
            }
        }

        return null;
    }

    /**
     * @param Element $element
     * @param Element $other
     * @param float $distance
     * @return bool
     */
    protected function isElementWithin(Element $element, Element $other, float $distance = 1.0): bool
    {
        return abs($element->getX() - $other->getX()) <= $distance
            && abs($element->getY() - $other->getY()) <= $distance
            && abs($element->getZ() - $other->getZ()) <= $distance;
    }

    /**
     * @param Element[] $elements
     * @return Element|null
     */
    protected function findStartingPoint(array$elements): ?Element
    {
        $minY = PHP_INT_MAX;
        $lowest = [];
        foreach ($elements as $element) {
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
