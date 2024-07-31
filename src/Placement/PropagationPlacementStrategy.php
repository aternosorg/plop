<?php

namespace Aternos\Plop\Placement;

use Aternos\Plop\Placement\Util\WeightedElement;
use Aternos\Plop\Structure\Elements\Element;

class PropagationPlacementStrategy extends PlacementStrategy
{
    /**
     * @var Element[]
     */
    protected array $availableElements = [];
    protected array $placedElements = [];

    public function __construct(
        public int   $perTick = 1,
        public int   $x = 0,
        public int   $y = 0,
        public int   $z = 0,
        public float $threshold = 0.5
    )
    {
    }

    public function getPlacements(): array
    {
        $this->availableElements = $this->getElements();

        $startElement = $this->findStartElement();
        $this->placeElement($startElement);

        while ($elements = $this->getElementsWithLowestWeight()) {
            shuffle($elements);
            foreach ($elements as $element) {
                $this->placeElement($element);
            }
        }

        return $this->generatePlacements($this->placedElements, $this->perTick);
    }

    /**
     * @param Element $element
     * @return void
     */
    protected function placeElement(Element $element): void
    {
        $this->placedElements[] = $element;
        $this->availableElements = array_filter($this->availableElements, fn($e) => $e !== $element);
    }

    /**
     * @return Element[]
     */
    protected function getElementsWithLowestWeight(): array
    {
        $lowestWeight = null;
        $elements = [];
        foreach ($this->getWeightedElements() as $weightedElement) {
            if ($lowestWeight === null || $weightedElement->getWeight() < $lowestWeight - $this->threshold) {
                $lowestWeight = $weightedElement->getWeight();
                $elements = [$weightedElement->getElement()];
            } elseif ($weightedElement->getWeight() >= $lowestWeight - $this->threshold
                && $weightedElement->getWeight() <= $lowestWeight + $this->threshold) {
                $elements[] = $weightedElement->getElement();
            }
        }
        return $elements;
    }

    /**
     * @return WeightedElement[]
     */
    protected function getWeightedElements(): array
    {
        $elements = [];
        foreach ($this->availableElements as $element) {
            $lowestDistance = $this->getLowestDistance($element);
            $elements[] = (new WeightedElement($element))->setWeight($lowestDistance);
        }
        return $elements;
    }

    protected function getLowestDistance(Element $element): float
    {
        $lowestDistance = null;
        foreach ($this->placedElements as $placedElement) {
            $distance = $this->getDistance($element, $placedElement);
            if ($lowestDistance === null || $distance < $lowestDistance) {
                $lowestDistance = $distance;
            }
        }
        return $lowestDistance;
    }

    protected function getDistance(Element $element1, Element $element2): float
    {
        return $this->getCoordinateDistance($element1->getX(), $element1->getY(), $element1->getZ(), $element2->getX(), $element2->getY(), $element2->getZ());
    }

    protected function getCoordinateDistance(int $x1, int $y1, int $z1, int $x2, int $y2, int $z2): float
    {
        return sqrt(
            pow($x1 - $x2, 2) +
            pow($y1 - $y2, 2) +
            pow($z1 - $z2, 2)
        );
    }

    protected function findStartElement(): Element
    {
        $currentElement = $this->availableElements[0];
        $currentDistance = null;
        foreach ($this->availableElements as $element) {
            $distance = $this->getCoordinateDistance($this->x, $this->y, $this->z, $element->getX(), $element->getY(), $element->getZ());
            if ($currentDistance === null || $distance < $currentDistance) {
                $currentElement = $element;
                $currentDistance = $distance;
            }
        }
        return $currentElement;
    }
}