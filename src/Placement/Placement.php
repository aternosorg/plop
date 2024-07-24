<?php

namespace Aternos\Plop\Placement;

use Aternos\Plop\Structure\Elements\Element;

class Placement
{
    /**
     * @param Element[] $elements
     * @param int $tick
     */
    public function __construct(protected array $elements = [], protected int $tick = 0)
    {
    }

    /**
     * @param Element $element
     * @return $this
     */
    public function addElement(Element $element): static
    {
        $this->elements[] = $element;
        return $this;
    }

    /**
     * @param array $elements
     * @return $this
     */
    public function setElements(array $elements): static
    {
        $this->elements = $elements;
        return $this;
    }

    /**
     * @return Element[]
     */
    public function getElements(): array
    {
        return $this->elements;
    }

    /**
     * @return int
     */
    public function getElementCount(): int
    {
        return count($this->elements);
    }

    public function getTick(): int
    {
        return $this->tick;
    }

    public function setTick(int $tick): static
    {
        $this->tick = $tick;
        return $this;
    }
}