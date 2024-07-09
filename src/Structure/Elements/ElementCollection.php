<?php

namespace Aternos\Plop\Structure\Elements;

use Aternos\Plop\Placement\Util\Axis;
use Generator;

class ElementCollection
{
    /**
     * @param Element[] $input
     * @return static
     */
    public static function fromArray(array $input): ElementCollection
    {
        $elements = [];
        foreach ($input as $element) {
            $x = intval($element->getX());
            $y = intval($element->getY());
            $z = intval($element->getZ());

            if (!isset($elements[$x])) {
                $elements[$x] = [];
            }

            if (!isset($elements[$x][$y])) {
                $elements[$x][$y] = [];
            }

            if (!isset($elements[$x][$y][$z])) {
                $elements[$x][$y][$z] = [$element];
            } else {
                $elements[$x][$y][$z][] = $element;
            }
        }

        return new static($elements);
    }

    /**
     * @param Element[][][][] $elements
     */
    private function __construct(protected array $elements)
    {
    }

    /**
     * @param int $x
     * @param int $y
     * @param int $z
     * @return Element[]
     */
    public function getAllAt(int $x, int $y, int $z): array
    {
        return $this->elements[$x][$y][$z] ?? [];
    }

    /**
     * @param int $x
     * @param int $y
     * @param int $z
     * @return Element|null
     */
    public function getAt(int $x, int $y, int $z): ?Element
    {
        return $this->getAllAt($x, $y, $z)[0] ?? null;
    }

    /**
     * @param int $x
     * @param int $y
     * @param int $z
     * @param int $distance
     * @return Generator<Element>
     */
    public function getAdjacent(int $x, int $y, int $z, int $distance): Generator
    {
        $adjacent = [];
        for ($i = -$distance; $i <= $distance; $i++) {
            for ($j = -$distance; $j <= $distance; $j++) {
                for ($k = -$distance; $k <= $distance; $k++) {
                    if ($i === 0 && $j === 0 && $k === 0) {
                        continue;
                    }
                    foreach ($this->getAllAt($x + $i, $y + $j, $z + $k) as $element) {
                        yield $element;
                    }
                }
            }
        }
    }

    /**
     * @param Element $element
     * @param int $distance
     * @return Generator<Element>
     */
    public function getAdjacentToElement(Element $element, int $distance): Generator
    {
        return $this->getAdjacent(intval($element->getX()), intval($element->getY()), intval($element->getZ()), $distance);
    }

    /**
     * @return Generator<Element>
     */
    public function getAll(): Generator
    {
        foreach ($this->elements as $x => $xElements) {
            foreach ($xElements as $y => $yElements) {
                foreach ($yElements as $z => $zElements) {
                    foreach ($zElements as $element) {
                        yield $element;
                    }
                }
            }
        }
    }

    /**
     * @param Element $element
     * @return bool
     */
    public function remove(Element $element): bool
    {
        $x = intval($element->getX());
        $y = intval($element->getY());
        $z = intval($element->getZ());

        $index = array_search($element, $this->elements[$x][$y][$z]);
        if ($index === false) {
            return false;
        }

        unset($this->elements[$x][$y][$z][$index]);
        return true;
    }

    /**
     * @return static
     */
    public function clone(): static
    {
        return new static($this->elements);
    }
}
