<?php

namespace Aternos\Plop\Structure\Elements;

use Aternos\Plop\Placement\Util\Axis;
use Generator;

class ElementCollection
{
    protected array $elements = [];

    /**
     * @param Element[] $input
     */
    public function __construct(array $input)
    {
        foreach ($input as $element) {
            $x = intval($element->getX());
            $y = intval($element->getY());
            $z = intval($element->getZ());

            if (!isset($this->elements[$x])) {
                $this->elements[$x] = [];
            }

            if (!isset($this->elements[$x][$y])) {
                $this->elements[$x][$y] = [];
            }

            if (!isset($this->elements[$x][$y][$z])) {
                $this->elements[$x][$y][$z] = [$element];
            } else {
                $this->elements[$x][$y][$z][] = $element;
            }
        }
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
}
