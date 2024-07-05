<?php

namespace Aternos\Plop\Structure\Elements;

class Entity extends Element
{
    public function getSummonCommand(): string
    {
        return "summon " . $this->getName() . " " . $this->getRelativeCoordinatesString() . " " . ($this->nbt ?? "");
    }
}