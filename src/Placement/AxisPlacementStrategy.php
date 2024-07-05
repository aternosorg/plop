<?php

namespace Aternos\Plop\Placement;

use Aternos\Plop\Placement\Util\Axis;
use Aternos\Plop\Placement\Util\AxisStrategy;
use Aternos\Plop\Structure\Elements\Element;

class AxisPlacementStrategy extends PlacementStrategy
{
    public function __construct(
        protected array $order = [Axis::X, Axis::Z, Axis::Y],
        protected AxisStrategy $strategy = AxisStrategy::ASCENDING,
        protected ?AxisStrategy $x = null,
        protected ?AxisStrategy $y = null,
        protected ?AxisStrategy $z = null,
        protected int $elementsPerTick = 1
    )
    {
    }

    public function getPlacements(): array
    {
        $placements = [];
        $elements = $this->getElements();
        usort($elements, function ($a, $b) {
            return $this->compareElements($a, $b);
        });
        $tick = 0;
        $equalElements = [];
        $placementElements = [];
        foreach ($elements as $element) {
            if (empty($equalElements)) {
                $equalElements[] = $element;
                continue;
            }
            if ($this->compareElements($equalElements[0], $element) === 0) {
                $equalElements[] = $element;
                continue;
            }

            shuffle($equalElements);
            foreach ($equalElements as $equalElement) {
                $placementElements[] = $equalElement;
                if (count($placementElements) >= $this->elementsPerTick) {
                    $placements[] = new Placement($placementElements, $tick);
                    $placementElements = [];
                    $tick++;
                }
            }
            $equalElements = [$element];
        }
        $placements[] = new Placement(array_merge($placementElements, $equalElements), $tick);
        return $placements;
    }

    protected function compareElements(Element $a, Element $b): int
    {
        $order = array_reverse($this->order);
        foreach ($order as $axis) {
            $strategy = match ($axis) {
                Axis::X => $this->getXStrategy(),
                Axis::Y => $this->getYStrategy(),
                Axis::Z => $this->getZStrategy()
            };
            $result = $this->compareOnAxis($strategy, $a->getAxis($axis), $b->getAxis($axis));
            if ($result !== 0) {
                return $result;
            }
        }
        return 0;
    }

    protected function compareOnAxis(AxisStrategy $strategy, float $a, float $b): int
    {
        return match ($strategy) {
            AxisStrategy::ASCENDING => $a <=> $b,
            AxisStrategy::DESCENDING => $b <=> $a,
            default => 0,
        };

    }

    protected function getXStrategy(): AxisStrategy
    {
        return $this->x ?? $this->strategy;
    }

    protected function getYStrategy(): AxisStrategy
    {
        return $this->y ?? $this->strategy;
    }

    protected function getZStrategy(): AxisStrategy
    {
        return $this->z ?? $this->strategy;
    }
}