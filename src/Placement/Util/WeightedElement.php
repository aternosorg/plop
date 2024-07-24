<?php

namespace Aternos\Plop\Placement\Util;

use Aternos\Plop\Structure\Elements\Element;

class WeightedElement
{
    public function __construct(protected Element $element, protected float $weight = 0)
    {
    }

    public function getElement(): Element
    {
        return $this->element;
    }

    public function setElement(Element $element): static
    {
        $this->element = $element;
        return $this;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): static
    {
        $this->weight = $weight;
        return $this;
    }
}