<?php

namespace Aternos\Plop\Placement;

use Aternos\Plop\Structure\Elements\Element;

class Placement
{
    /**
     * @param Element[] $elements
     * @param int $delay
     */
    public function __construct(protected array $elements = [], protected int $delay = 0)
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
     * @param int $delay
     * @return $this
     */
    public function setDelay(int $delay): static
    {
        $this->delay = $delay;
        return $this;
    }

    /**
     * @return int
     */
    public function getDelay(): int
    {
        return $this->delay;
    }
}